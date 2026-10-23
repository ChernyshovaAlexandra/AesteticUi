@props(['checked' => false, 'disabled' => false, 'value'])
<label class="aui-choice">
    <input type="radio" value="{{ $value }}" {{ $attributes->class(['aui-choice__input']) }} @checked($checked) @disabled($disabled)>
    <span class="aui-choice__radio" aria-hidden="true"></span>
    <span class="aui-choice__label">{{ $slot }}</span>
</label>
