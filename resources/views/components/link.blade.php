@props(['href'])
<a href="{{ $href }}" {{ $attributes->class(['aui-link']) }}>{{ $slot }}</a>
