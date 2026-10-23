@props(['tone' => 'neutral'])
@php
    if (! in_array($tone, ['neutral', 'info', 'progress', 'success', 'warning', 'danger', 'cancelled'], true)) {
        throw new \InvalidArgumentException('Unsupported Aestetic UI status tone.');
    }
@endphp
<span {{ $attributes->class(['aui-status', 'aui-status--'.$tone]) }}>{{ $slot }}</span>
