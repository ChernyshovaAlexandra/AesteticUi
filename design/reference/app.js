/* Local interactions for reviewing the design. No network, storage or backend. */
(() => {
  const demo = window.AesteticReference;
  const view = demo.views;
  const app = document.querySelector('#app');
  const dialog = document.querySelector('#action-dialog');
  const drawer = document.querySelector('#navigation-drawer');
  const toast = document.querySelector('#toast');
  const state = {
    customers: demo.customers.map(person => ({ ...person })),
    orders: demo.orders.map(order => ({ ...order })),
    route: { screen: 'orders', customer: '' },
    list: { search: '', status: 'all', from: '', to: '', sort: 'date', direction: 'desc', page: 1, pageSize: 8 },
    selection: new Set(), filtersOpen: false, customerTab: 'overview',
    theme: 'light', density: 'comfortable', reviewState: 'normal', navigationOpen: false, reviewOpen: false,
    dirty: false, draft: null, currentHash: '', pendingHash: '', allowNavigation: false
  };
  let toastTimer;
  let dialogOpener;

  /** Keep live changes local to this page; provide explicit feedback. */
  function notify(message) {
    clearTimeout(toastTimer);
    toast.textContent = message;
    toast.hidden = false;
    toastTimer = setTimeout(() => { toast.hidden = true; }, 4500);
  }

  function filteredOrders() {
    const query = state.list.search.trim().toLocaleLowerCase('ru');
    return state.orders.filter(order => {
      const person = view.customer(state, order.customer);
      const matchesQuery = !query || `${order.id} ${order.item} ${person.name} ${person.email} ${person.company}`.toLocaleLowerCase('ru').includes(query);
      return matchesQuery && (!state.route.customer || order.customer === state.route.customer)
        && (state.list.status === 'all' || order.status === state.list.status)
        && (!state.list.from || order.date >= state.list.from) && (!state.list.to || order.date <= state.list.to);
    }).sort((a, b) => {
      const result = state.list.sort === 'amount' ? a.amount - b.amount : a.date.localeCompare(b.date) || Number(a.id) - Number(b.id);
      return state.list.direction === 'asc' ? result : -result;
    });
  }

  /** Preserve typing and scroll while replacing the result composition. */
  function render({ focusHeading = false, preserveInput = false } = {}) {
    const previousFocus = document.activeElement?.id;
    const position = previousFocus === 'order-search' ? document.activeElement.selectionStart : null;
    const filtered = filteredOrders();
    state.list.page = Math.max(1, Math.min(state.list.page, Math.max(1, Math.ceil(filtered.length / state.list.pageSize))));
    const paged = filtered.slice((state.list.page - 1) * state.list.pageSize, state.list.page * state.list.pageSize);
    const content = state.route.screen === 'edit' ? view.editScreen(state) : state.route.screen === 'customer' ? view.customerScreen(state) : view.ordersScreen(state, filtered, paged);
    app.innerHTML = view.shell(state, content);
    drawer.innerHTML = view.navigation(state, true);
    document.documentElement.dataset.theme = state.theme;
    document.documentElement.dataset.density = state.density;
    const person = view.customer(state, state.route.customer);
    document.title = `${state.route.screen === 'orders' ? 'Заказы' : state.route.screen === 'edit' ? 'Редактирование клиента' : person.name} · Aestetic UI`;
    const selectAll = document.querySelector('#select-visible');
    if (selectAll) selectAll.indeterminate = paged.some(order => state.selection.has(order.id)) && !paged.every(order => state.selection.has(order.id));
    if (preserveInput && previousFocus) {
      const control = document.getElementById(previousFocus);
      control?.focus({ preventScroll: true });
      if (position !== null && control?.type === 'search') control.setSelectionRange(position, position);
    }
    if (focusHeading) app.querySelector('h1')?.focus({ preventScroll: true });
  }

  function showDialog(title, body, actions = '') {
    dialogOpener = document.activeElement;
    dialog.innerHTML = `<div class="dialog-header"><h2 id="dialog-title">${title}</h2><button type="button" class="icon-button" data-action="close-dialog" aria-label="Закрыть диалог">${view.icon('close')}</button></div>${body}${actions ? `<div class="dialog-actions">${actions}</div>` : ''}`;
    if (!dialog.open) dialog.showModal();
    dialog.querySelector('[data-initial-focus], [autofocus], [data-action="close-dialog"]')?.focus();
  }

  function confirmNavigation(hash) {
    state.pendingHash = hash;
    showDialog('Оставить изменения?', '<p class="dialog-copy">В форме есть несохранённые изменения. Если уйти со страницы, они будут потеряны.</p>', `${view.button('Продолжить редактирование', { action: 'close-dialog', extra: 'data-initial-focus' })}${view.button('Выйти без сохранения', { action: 'discard-draft', variant: 'primary' })}`);
  }

  /** Route changes never silently discard an edited customer. */
  function navigate(hash) {
    if (hash === state.currentHash) return;
    if (state.dirty && !state.allowNavigation) { confirmNavigation(hash); return; }
    state.allowNavigation = false;
    state.dirty = false;
    state.draft = null;
    if (drawer.open) drawer.close();
    if (location.hash === hash) loadRoute();
    else location.hash = hash;
  }

  function loadRoute() {
    const hash = location.hash || '#/orders';
    if (state.dirty && !state.allowNavigation && hash !== state.currentHash) {
      history.replaceState(null, '', state.currentHash);
      confirmNavigation(hash);
      return;
    }
    state.allowNavigation = false;
    const [path, query] = hash.slice(1).split('?');
    const match = path.match(/^\/customers\/(c-\d+)(\/edit)?$/);
    const person = match && view.customer(state, match[1]);
    if (path === '/orders') {
      const requestedCustomer = new URLSearchParams(query || '').get('customer');
      state.route = { screen: 'orders', customer: view.customer(state, requestedCustomer)?.id || '' };
    } else if (person) {
      state.route = { screen: match[2] ? 'edit' : 'customer', customer: person.id };
      state.navigationOpen = true;
      state.customerTab = 'overview';
      state.draft = match[2] ? { ...person } : null;
    } else {
      history.replaceState(null, '', '#/orders');
      state.route = { screen: 'orders', customer: '' };
    }
    state.dirty = false;
    state.selection.clear();
    state.currentHash = location.hash || '#/orders';
    render({ focusHeading: true });
    window.scrollTo({ top: 0, behavior: 'instant' });
  }

  function rememberDraft() {
    const form = document.querySelector('#customer-form');
    if (!form) return;
    const values = new FormData(form);
    state.draft = { ...state.draft, ...Object.fromEntries(values), notifications: values.has('notifications') };
    const original = view.customer(state, state.route.customer);
    state.dirty = ['name', 'company', 'email', 'phone', 'city', 'street', 'postal', 'country', 'note', 'notifications'].some(key => state.draft[key] !== original[key]);
    const save = document.querySelector('#save-customer');
    const saveState = document.querySelector('#save-state');
    if (save) save.disabled = !state.dirty;
    if (saveState) {
      saveState.textContent = state.dirty ? 'Есть несохранённые изменения' : 'Нет новых изменений';
      saveState.dataset.dirty = String(state.dirty);
    }
    const counter = document.querySelector('#note-counter');
    if (counter) counter.textContent = `${state.draft.note.length}/400`;
  }

  function showOrder(id) {
    const order = state.orders.find(item => item.id === id);
    if (!order) return;
    const person = view.customer(state, order.customer);
    showDialog(`Заказ #${order.id}`, `<p class="dialog-copy">${view.escape(order.item)}</p><dl class="context-list"><div><dt>Клиент</dt><dd>${view.escape(person.name)}</dd></div><div><dt>Количество</dt><dd>${order.quantity}</dd></div><div><dt>Сумма</dt><dd>${view.money(order.amount)}</dd></div><div><dt>Статус</dt><dd>${view.status(order.status)}</dd></div><div><dt>Дата</dt><dd>${view.date(order.date)} 2026</dd></div></dl>`, `${view.button('Закрыть', { action: 'close-dialog' })}${view.button('Открыть клиента', { href: `#/customers/${person.id}`, variant: 'primary' })}`);
  }

  function createOrder() {
    showDialog('Новый заказ', `<p class="dialog-copy">Добавьте заказ для просмотра поведения списка. Он сохранится только до перезагрузки страницы.</p><form id="new-order-form"><div class="fields-grid"><div class="field field--wide"><label for="new-order-customer">Клиент</label><select id="new-order-customer" name="customer">${state.customers.map(person => `<option value="${person.id}">${view.escape(person.name)}</option>`).join('')}</select></div><div class="field field--wide"><label for="new-order-item">Название</label><input id="new-order-item" name="item" required maxlength="120" placeholder="Например, настольная лампа" data-initial-focus></div><div class="field"><label for="new-order-quantity">Количество</label><input id="new-order-quantity" name="quantity" type="number" min="1" max="999" step="1" value="1" required></div><div class="field"><label for="new-order-amount">Сумма, ₽</label><input id="new-order-amount" name="amount" type="number" min="1" step="1" required></div></div><div class="dialog-actions">${view.button('Отмена', { action: 'close-dialog' })}<button class="button button--primary" type="submit">Создать заказ</button></div></form>`);
    dialog.querySelector('[data-initial-focus]')?.focus();
  }

  function exportOrders() {
    const cell = value => `"${String(value).replace(/^[=+@-]/, "'$&").replaceAll('"', '""')}"`;
    const lines = [['Номер', 'Клиент', 'Статус', 'Сумма', 'Дата'], ...filteredOrders().map(order => [order.id, view.customer(state, order.customer).name, demo.statuses[order.status].label, order.amount, order.date])];
    const blob = new Blob(['\uFEFF' + lines.map(row => row.map(cell).join(';')).join('\r\n')], { type: 'text/csv;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'aestetic-demo-orders.csv';
    link.click();
    setTimeout(() => URL.revokeObjectURL(url), 1000);
    notify('Экспортированы заказы с учётом фильтров.');
  }

  const actions = {
    'toggle-filters': () => { state.filtersOpen = !state.filtersOpen; render(); document.querySelector('[data-action="toggle-filters"]')?.focus({ preventScroll: true }); },
    'reset-filters': () => { Object.assign(state.list, { search: '', status: 'all', from: '', to: '', page: 1 }); state.selection.clear(); if (state.route.customer) navigate('#/orders'); else render(); },
    'reset-review': () => { state.reviewState = 'normal'; render(); },
    'clear-selection': () => { state.selection.clear(); render(); },
    'complete-selected': () => {
      const count = state.selection.size;
      state.orders.forEach(order => { if (state.selection.has(order.id)) order.status = 'completed'; });
      state.selection.clear(); render(); notify(`Выполненными отмечено заказов: ${count}. Изменение действует только в демо.`);
    },
    'open-navigation': () => { drawer.innerHTML = view.navigation(state, true); drawer.showModal(); },
    'close-navigation': () => drawer.close(),
    'close-dialog': () => dialog.close(),
    'discard-draft': () => { const hash = state.pendingHash; state.dirty = false; state.draft = null; dialog.close(); navigate(hash); },
    'create-order': createOrder,
    'export': exportOrders,
    'help': () => showDialog('Три экрана. Одна система.', '<p class="dialog-copy">Это дизайн-прототип Aestetic UI: список заказов, карточка клиента и форма редактирования.</p><p class="dialog-copy">Откройте клиента из таблицы, переключите вкладки и измените его данные. В меню слева можно выбрать оформление, плотность и состояние экрана.</p><p class="dialog-copy">Все данные вымышлены. Изменения хранятся только в памяти этой страницы и исчезают после перезагрузки. Сервер, библиотечный API и MCP здесь не подключены.</p>', view.button('Понятно', { action: 'close-dialog', variant: 'primary' }))
  };

  document.addEventListener('click', event => {
    const skip = event.target.closest('a[href="#main-content"]');
    if (skip) { event.preventDefault(); document.querySelector('#main-content')?.focus(); return; }
    const section = event.target.closest('[data-section]');
    if (section) { event.preventDefault(); const target = document.getElementById(section.dataset.section)?.closest('section'); target?.scrollIntoView({ behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth', block: 'start' }); return; }
    const link = event.target.closest('a[href^="#/"]');
    if (link && !event.metaKey && !event.ctrlKey && !event.shiftKey && !event.altKey) { event.preventDefault(); if (dialog.open) dialog.close(); navigate(link.getAttribute('href')); return; }
    const action = event.target.closest('[data-action]');
    if (action && actions[action.dataset.action]) { actions[action.dataset.action](); return; }
    const order = event.target.closest('[data-order]');
    if (order) { showOrder(order.dataset.order); return; }
    const scope = event.target.closest('[data-status]');
    if (scope) { state.list.status = scope.dataset.status; state.list.page = 1; state.selection.clear(); render(); document.querySelector(`[data-status="${state.list.status}"]`)?.focus({ preventScroll: true }); return; }
    const sort = event.target.closest('[data-sort]');
    if (sort) { state.list.direction = state.list.sort === sort.dataset.sort && state.list.direction === 'desc' ? 'asc' : 'desc'; state.list.sort = sort.dataset.sort; render(); document.querySelector(`[data-sort="${state.list.sort}"]`)?.focus({ preventScroll: true }); return; }
    const page = event.target.closest('[data-page]');
    if (page && !page.disabled) { state.list.page = Number(page.dataset.page); state.selection.clear(); render(); return; }
    const tab = event.target.closest('[data-tab]');
    if (tab) { state.customerTab = tab.dataset.tab; render(); document.getElementById(`tab-${state.customerTab}`)?.focus({ preventScroll: true }); }
  });

  document.addEventListener('input', event => {
    if (event.target.id === 'order-search') { state.list.search = event.target.value; state.list.page = 1; state.selection.clear(); render({ preserveInput: true }); }
    if (event.target.closest('#customer-form')) rememberDraft();
  });

  document.addEventListener('change', event => {
    const control = event.target;
    if (control.dataset.preference) {
      rememberDraft();
      state[control.dataset.preference] = control.value;
      const wasDrawerOpen = drawer.open;
      const preference = control.dataset.preference;
      render();
      const preferenceRoot = wasDrawerOpen ? drawer : app;
      preferenceRoot.querySelector('.review-tools').open = true;
      preferenceRoot.querySelector(`[data-preference="${preference}"]`)?.focus({ preventScroll: true });
    }
    if (control.id === 'page-size') { state.list.pageSize = Number(control.value); state.list.page = 1; state.selection.clear(); render(); document.querySelector('#page-size')?.focus({ preventScroll: true }); }
    if (control.dataset.selectOrder) { control.checked ? state.selection.add(control.dataset.selectOrder) : state.selection.delete(control.dataset.selectOrder); const id = control.dataset.selectOrder; render(); document.querySelector(`[data-select-order="${id}"]`)?.focus({ preventScroll: true }); }
    if (control.id === 'select-visible') { const visible = filteredOrders().slice((state.list.page - 1) * state.list.pageSize, state.list.page * state.list.pageSize); visible.forEach(order => control.checked ? state.selection.add(order.id) : state.selection.delete(order.id)); render(); document.querySelector('#select-visible')?.focus({ preventScroll: true }); }
    if (control.closest('#customer-form')) rememberDraft();
  });

  document.addEventListener('submit', event => {
    if (event.target.id === 'advanced-filters') {
      event.preventDefault();
      const data = new FormData(event.target);
      if (data.get('from') && data.get('to') && data.get('from') > data.get('to')) { const error = document.querySelector('#filter-error'); error.textContent = 'Начальная дата должна быть раньше конечной.'; error.hidden = false; return; }
      Object.assign(state.list, { status: data.get('status'), from: data.get('from'), to: data.get('to'), page: 1 });
      state.selection.clear(); render(); return;
    }
    if (event.target.id === 'new-order-form') {
      event.preventDefault();
      const data = new FormData(event.target);
      const item = String(data.get('item')).trim();
      if (!item) { const control = event.target.elements.namedItem('item'); control.setCustomValidity('Введите название заказа.'); control.reportValidity(); return; }
      const id = String(Math.max(...state.orders.map(order => Number(order.id))) + 1);
      state.orders.unshift({ id, item, customer: data.get('customer'), quantity: Number(data.get('quantity')), amount: Number(data.get('amount')), status: 'new', date: '2026-10-23' });
      dialog.close(); Object.assign(state.list, { search: '', status: 'all', from: '', to: '', page: 1, sort: 'date', direction: 'desc' }); state.selection.clear(); state.reviewState = 'normal';
      if (state.currentHash !== '#/orders') navigate('#/orders'); else render();
      notify(`Заказ #${id} добавлен в демо.`); return;
    }
    if (event.target.id === 'customer-form') {
      event.preventDefault(); rememberDraft();
      const errors = [];
      const form = event.target;
      ['name', 'email'].forEach(name => {
        const control = form.elements[name];
        const message = !control.value.trim() ? `Укажите ${name === 'name' ? 'имя и фамилию' : 'электронную почту'}.` : name === 'email' && control.validity.typeMismatch ? 'Проверьте адрес электронной почты.' : '';
        control.setAttribute('aria-invalid', String(Boolean(message)));
        const output = document.getElementById(`customer-${name}-error`);
        output.textContent = message; output.hidden = !message;
        if (message) errors.push({ name, message });
      });
      const summary = document.querySelector('#validation-summary');
      summary.hidden = !errors.length;
      if (errors.length) { summary.innerHTML = `<strong>Проверьте данные перед сохранением</strong><ul>${errors.map(error => `<li><a href="#customer-${error.name}" data-error-focus="customer-${error.name}">${error.message}</a></li>`).join('')}</ul>`; form.elements[errors[0].name].focus(); return; }
      const person = view.customer(state, state.route.customer);
      Object.assign(person, state.draft, { name: state.draft.name.trim(), email: state.draft.email.trim(), updated: '23 октября 2026' });
      person.initials = person.name.split(/\s+/).slice(0, 2).map(part => part[0]).join('').toUpperCase();
      state.dirty = false; state.draft = null; navigate(`#/customers/${person.id}`); notify('Изменения сохранены в демо. После перезагрузки вернутся исходные данные.');
    }
  });

  document.addEventListener('click', event => {
    const target = event.target.closest('[data-error-focus]');
    if (target) { event.preventDefault(); document.getElementById(target.dataset.errorFocus)?.focus(); }
  });
  document.addEventListener('input', event => { if (event.target.id === 'new-order-item') event.target.setCustomValidity(''); });
  document.addEventListener('toggle', event => {
    if (event.target.isConnected && event.target.dataset.disclosure) state[event.target.dataset.disclosure] = event.target.open;
  }, true);
  document.addEventListener('keydown', event => {
    const tab = event.target.closest('[data-tab]');
    if (tab && ['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)) {
      event.preventDefault(); const tabs = ['overview', 'orders', 'history']; const index = tabs.indexOf(state.customerTab);
      state.customerTab = tabs[event.key === 'Home' ? 0 : event.key === 'End' ? 2 : (index + (event.key === 'ArrowRight' ? 1 : 2)) % 3];
      render(); document.getElementById(`tab-${state.customerTab}`)?.focus({ preventScroll: true });
    }
    if (event.key === '/' && !event.metaKey && !event.ctrlKey && !event.altKey && !/INPUT|TEXTAREA|SELECT/.test(event.target.tagName) && !event.target.isContentEditable && !dialog.open && !drawer.open) { const search = document.querySelector('#order-search'); if (search) { event.preventDefault(); search.focus(); } }
  });
  dialog.addEventListener('close', () => { if (dialogOpener?.isConnected) dialogOpener.focus({ preventScroll: true }); });
  drawer.addEventListener('close', () => document.querySelector('[data-action="open-navigation"]')?.focus({ preventScroll: true }));
  window.addEventListener('beforeunload', event => { if (state.dirty) { event.preventDefault(); event.returnValue = ''; } });
  window.addEventListener('hashchange', loadRoute);
  if (!location.hash) history.replaceState(null, '', '#/orders');
  loadRoute();
})();
