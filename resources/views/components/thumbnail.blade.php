@props(['src', 'alt'])
<img src="{{ $src }}" alt="{{ $alt }}" {{ $attributes->class(['aui-thumbnail']) }} loading="lazy">
