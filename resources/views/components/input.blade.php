@props(['type' => 'text', 'value' => null, 'invalid' => false])
@php
    if (! in_array($type, ['text', 'email', 'password', 'search', 'tel', 'url', 'number'], true)) {
        throw new \InvalidArgumentException('Input supports text, email, password, search, tel, url and number. Date/time require dedicated components.');
    }
@endphp
<input type="{{ $type }}" @if($value !== null) value="{{ $value }}" @endif {{ $attributes->class(['aui-input'])->merge(['aria-invalid' => $invalid ? 'true' : null]) }}>
