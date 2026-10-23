@props(['label' => 'Загрузка'])
<span {{ $attributes->class(['aui-spinner']) }} role="status">
    <x-ui::icon name="refresh-cw" />
    <span class="aui-visually-hidden">{{ $label }}</span>
</span>
