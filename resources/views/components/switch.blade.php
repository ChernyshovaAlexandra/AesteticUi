@props(['checked' => false, 'disabled' => false, 'value' => '1'])
<label class="aui-choice">
    <input type="checkbox" role="switch" value="{{ $value }}" {{ $attributes->class(['aui-choice__input']) }} @checked($checked) @disabled($disabled)>
    <span class="aui-switch-track" aria-hidden="true"></span>
    <span class="aui-choice__label">{{ $slot }}</span>
</label>
