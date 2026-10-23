const managed = new Map();
let nextId = 0;

function enhanceSelect(wrapper) {
    const select = wrapper.querySelector('select');
    const trigger = wrapper.querySelector('.aui-select__trigger');
    const list = wrapper.querySelector('.aui-select__list');
    const value = trigger.querySelector('[data-aui-select-value]');
    const abort = new AbortController();
    const on = (target, event, listener) => target?.addEventListener(event, listener, { signal: abort.signal });
    const uid = `aui-select-${++nextId}`;
    const original = { tabindex: select.getAttribute('tabindex'), hidden: select.getAttribute('aria-hidden') };
    const error = document.createElement('span');
    error.className = 'aui-select__error';
    error.id = `${uid}-error`;
    error.hidden = true;
    error.setAttribute('role', 'alert');
    wrapper.append(error);
    list.id = `${uid}-list`;
    trigger.setAttribute('aria-controls', list.id);
    if (typeof list.showPopover === 'function') list.setAttribute('popover', 'manual');
    let open = false;
    let active = -1;
    let search = '';
    let searchTimer;
    let nodes = [];

    const options = () => Array.from(select.options);
    const enabled = () => options().map((option, index) => option.disabled ? -1 : index).filter(index => index >= 0);

    function close() {
        if (open && list.hasAttribute('popover') && list.isConnected) list.hidePopover();
        open = false;
        list.hidden = true;
        trigger.setAttribute('aria-expanded', 'false');
        trigger.removeAttribute('aria-activedescendant');
    }

    function activate(index) {
        active = index;
        nodes.forEach((node, i) => node.dataset.active = String(i === active));
        if (active >= 0 && nodes[active]) {
            trigger.setAttribute('aria-activedescendant', nodes[active].id);
            nodes[active].scrollIntoView({ block: 'nearest' });
        }
    }

    function expand() {
        if (select.disabled || ! enabled().length || open) return;
        open = true;
        list.hidden = false;
        trigger.setAttribute('aria-expanded', 'true');
        if (list.hasAttribute('popover')) list.showPopover();
        const rect = trigger.getBoundingClientRect();
        const below = innerHeight - rect.bottom - 8;
        const above = rect.top - 8;
        const up = below < 160 && above > below;
        const height = Math.max(40, Math.min(240, up ? above : below));
        Object.assign(list.style, {
            width: `${Math.min(rect.width, innerWidth - 16)}px`,
            maxHeight: `${height}px`,
            left: `${Math.max(8, Math.min(rect.left, innerWidth - rect.width - 8))}px`,
            top: up ? 'auto' : `${rect.bottom + 4}px`,
            bottom: up ? `${innerHeight - rect.top + 4}px` : 'auto',
        });
        activate(enabled().includes(select.selectedIndex) ? select.selectedIndex : enabled()[0]);
    }

    function commit(index) {
        if (! enabled().includes(index)) return;
        select.selectedIndex = index;
        select.dispatchEvent(new Event('input', { bubbles: true }));
        select.dispatchEvent(new Event('change', { bubbles: true }));
        close();
        trigger.focus();
    }

    function render() {
        close();
        value.textContent = select.selectedOptions[0]?.textContent ?? '';
        trigger.disabled = select.disabled;
        trigger.setAttribute('aria-required', String(select.required));
        trigger.setAttribute('aria-invalid', select.getAttribute('aria-invalid') ?? 'false');
        const labels = Array.from(select.labels ?? []).map((label, index) => {
            label.id ||= `${uid}-label-${index}`;
            return label.id;
        });
        const labelledBy = select.getAttribute('aria-labelledby') || labels.join(' ');
        if (labelledBy) {
            trigger.removeAttribute('aria-label');
            list.removeAttribute('aria-label');
            trigger.setAttribute('aria-labelledby', labelledBy);
            list.setAttribute('aria-labelledby', labelledBy);
        } else {
            trigger.removeAttribute('aria-labelledby');
            list.removeAttribute('aria-labelledby');
            const label = select.getAttribute('aria-label') || 'Выбор значения';
            trigger.setAttribute('aria-label', label);
            list.setAttribute('aria-label', label);
        }
        const describedBy = select.getAttribute('aria-describedby');
        if (describedBy) trigger.setAttribute('aria-describedby', describedBy);
        else trigger.removeAttribute('aria-describedby');
        error.hidden = true;
        list.replaceChildren();
        nodes = options().map((option, index) => {
            const node = document.createElement('div');
            node.id = `${uid}-option-${index}`;
            node.className = 'aui-select__option';
            node.setAttribute('role', 'option');
            node.setAttribute('aria-selected', String(option.selected));
            node.setAttribute('aria-disabled', String(option.disabled));
            node.textContent = option.label;
            // These nodes are replaced on refresh, so their listeners are owned by the nodes.
            node.addEventListener('pointerdown', event => event.preventDefault());
            node.addEventListener('click', event => { event.preventDefault(); commit(index); });
            node.addEventListener('pointermove', () => { if (! option.disabled) activate(index); });
            list.append(node);
            return node;
        });
    }

    on(trigger, 'click', () => open ? close() : expand());
    on(trigger, 'keydown', event => {
        const items = enabled();
        if (['ArrowDown', 'ArrowUp', 'Home', 'End'].includes(event.key)) {
            event.preventDefault();
            if (! open) { expand(); return; }
            const index = items.indexOf(active);
            const next = event.key === 'Home' ? 0 : event.key === 'End' ? items.length - 1
                : Math.max(0, Math.min(items.length - 1, index + (event.key === 'ArrowDown' ? 1 : -1)));
            activate(items[next]);
        } else if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            if (open) commit(active); else expand();
        } else if (event.key === 'Escape') {
            event.preventDefault(); close();
        } else if (event.key === 'Tab') {
            close();
        } else if (event.key.length === 1 && ! event.ctrlKey && ! event.metaKey && ! event.altKey) {
            event.preventDefault();
            expand();
            search += event.key.toLocaleLowerCase();
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => { search = ''; }, 600);
            const found = items.find(index => options()[index].label.toLocaleLowerCase().startsWith(search));
            if (found !== undefined) activate(found);
        }
    });
    on(document, 'pointerdown', event => { if (! wrapper.contains(event.target)) close(); });
    on(trigger, 'blur', close);
    on(window, 'resize', close);
    on(window, 'scroll', event => { if (open && ! list.contains(event.target)) close(); });
    on(select, 'change', render);
    on(select, 'invalid', event => {
        event.preventDefault();
        close();
        error.textContent = select.validationMessage;
        error.hidden = false;
        trigger.setAttribute('aria-invalid', 'true');
        trigger.setAttribute('aria-describedby', [select.getAttribute('aria-describedby'), error.id].filter(Boolean).join(' '));
        trigger.focus();
    });
    on(select.form, 'reset', event => queueMicrotask(() => { if (! event.defaultPrevented) render(); }));
    Array.from(select.labels ?? []).forEach(label => on(label, 'click', event => {
        if (! wrapper.contains(event.target)) { event.preventDefault(); trigger.focus(); }
    }));
    const observer = new MutationObserver(render);
    observer.observe(select, { subtree: true, childList: true, characterData: true, attributes: true,
        attributeFilter: ['disabled', 'selected', 'value', 'label', 'required', 'aria-invalid', 'aria-describedby', 'aria-label', 'aria-labelledby'] });
    select.setAttribute('aria-hidden', 'true');
    select.tabIndex = -1;
    wrapper.dataset.auiEnhanced = 'true';
    trigger.hidden = false;
    render();
    return () => {
        close(); abort.abort(); observer.disconnect(); clearTimeout(searchTimer);
        original.tabindex === null ? select.removeAttribute('tabindex') : select.setAttribute('tabindex', original.tabindex);
        original.hidden === null ? select.removeAttribute('aria-hidden') : select.setAttribute('aria-hidden', original.hidden);
        delete wrapper.dataset.auiEnhanced;
        trigger.hidden = true;
        list.replaceChildren();
        error.remove();
    };
}

export function enhance(root = document) {
    const find = selector => [...(root.matches?.(selector) ? [root] : []), ...root.querySelectorAll(selector)];
    find('[data-aui-select]').forEach(element => {
        if (! managed.has(element)) managed.set(element, enhanceSelect(element));
    });
    find('[data-aui-indeterminate]').forEach(input => {
        if (managed.has(input)) return;
        input.indeterminate = true;
        const form = input.form;
        const reset = event => queueMicrotask(() => { if (! event.defaultPrevented) input.indeterminate = true; });
        form?.addEventListener('reset', reset);
        managed.set(input, () => form?.removeEventListener('reset', reset));
    });
    find('[data-aui-avatar-image]').forEach(image => {
        if (managed.has(image)) return;
        const fail = () => { image.hidden = true; };
        const loaded = () => { image.hidden = false; };
        image.addEventListener('error', fail);
        image.addEventListener('load', loaded);
        if (image.complete && ! image.naturalWidth) fail();
        managed.set(image, () => { image.removeEventListener('error', fail); image.removeEventListener('load', loaded); });
    });
}

export function destroy(root = document) {
    for (const [element, cleanup] of managed) {
        if (element === root || root.contains(element)) { cleanup(); managed.delete(element); }
    }
}

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', () => enhance(), { once: true });
else enhance();
