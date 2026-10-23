@props(['variant' => 'primary', 'type' => 'button', 'disabled' => false, 'loading' => false])

@php
    if (! in_array($variant, ['primary', 'outline'], true)) {
        throw new \InvalidArgumentException('Aestetic UI button variant must be primary or outline.');
    }

    if (! in_array($type, ['button', 'submit', 'reset'], true)) {
        throw new \InvalidArgumentException('Aestetic UI button type must be button, submit or reset.');
    }
@endphp

<button type="{{ $type }}" {{ $attributes->except(['disabled', 'aria-busy'])->class(['aui-button', 'aui-button--'.$variant]) }} @disabled($disabled || $loading) @if($loading) aria-busy="true" @endif>
    @if($loading)
        <x-ui::spinner label="Выполняется" />
    @endif
    {{ $slot }}
</button>
