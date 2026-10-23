@props(['theme' => null, 'density' => null])

@php
    $theme ??= config('aestetic-ui.theme', 'system');
    $density ??= config('aestetic-ui.density', 'comfortable');

    if (! in_array($theme, ['light', 'dark', 'system'], true)) {
        throw new \InvalidArgumentException('Aestetic UI theme must be light, dark or system.');
    }

    if (! in_array($density, ['comfortable', 'compact'], true)) {
        throw new \InvalidArgumentException('Aestetic UI density must be comfortable or compact.');
    }
@endphp

<div {{ $attributes->except(['data-aui-theme', 'data-aui-density'])->class(['aui-scope']) }} data-aui-theme="{{ $theme }}" data-aui-density="{{ $density }}">
    {{ $slot }}
</div>
