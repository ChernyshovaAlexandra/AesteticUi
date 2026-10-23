@props(['checked' => false, 'disabled' => false, 'indeterminate' => false, 'value' => '1'])
<label class="aui-choice">
    <input type="checkbox" value="{{ $value }}" {{ $attributes->class(['aui-choice__input']) }} @checked($checked) @disabled($disabled) @if($indeterminate) data-aui-indeterminate @endif>
    <span class="aui-choice__box" aria-hidden="true"><x-ui::icon name="check" /></span>
    <span class="aui-choice__label">{{ $slot }}</span>
</label>
