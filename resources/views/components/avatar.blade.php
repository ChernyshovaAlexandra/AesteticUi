@props(['name', 'src' => null, 'initials' => null])
@php
    $parts = preg_split('/\s+/u', trim($name), -1, PREG_SPLIT_NO_EMPTY);
    $initials ??= implode('', array_map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)), array_slice($parts, 0, 2)));
@endphp
<span {{ $attributes->class(['aui-avatar']) }} role="img" aria-label="{{ $name }}">
    <span aria-hidden="true">{{ $initials }}</span>
    @if($src)<img src="{{ $src }}" alt="" data-aui-avatar-image>@endif
</span>
