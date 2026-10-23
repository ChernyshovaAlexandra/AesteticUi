@props(['options' => [], 'value' => null, 'placeholder' => 'Выберите значение', 'disabled' => false, 'invalid' => false])
@php
    if ($attributes->has('multiple')) {
        throw new \InvalidArgumentException('This Select contract supports one value. Multiple selection is not implemented yet.');
    }
@endphp
<div {{ $attributes->only(['class', 'style'])->class(['aui-select']) }} data-aui-select>
    <select {{ $attributes->except(['class', 'style'])->class(['aui-select__native'])->merge(['aria-invalid' => $invalid ? 'true' : null]) }} @disabled($disabled)>
        @if($placeholder !== null)
            <option value="" @selected($value === null || (string) $value === '')>{{ $placeholder }}</option>
        @endif
        @foreach($options as $option)
            <option value="{{ $option['value'] }}" @selected($value !== null && (string) $value === (string) $option['value']) @disabled($option['disabled'] ?? false)>{{ $option['label'] }}</option>
        @endforeach
    </select>
    <button class="aui-select__trigger" type="button" role="combobox" aria-haspopup="listbox" aria-expanded="false" hidden>
        <span data-aui-select-value></span><x-ui::icon name="chevron-down" />
    </button>
    <div class="aui-select__list" role="listbox" hidden></div>
</div>
