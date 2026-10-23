@props(['title', 'description' => null, 'groups', 'metadata', 'options', 'current' => null, 'section' => 'components'])
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — Aestetic UI</title>
    <x-ui::assets />
    <link rel="stylesheet" href="{{ asset('catalog.css') }}">
</head>
<body class="wb-document">
<x-ui::scope :theme="$options['theme']" :density="$options['density']" class="wb-shell">
    <a class="wb-skip" href="#catalog-content">К содержимому</a>
    <header class="wb-topbar">
        <a class="wb-brand" href="{{ route('catalog.index', $options) }}">Aestetic <span>UI</span></a>
        <nav class="wb-sections" aria-label="Разделы библиотеки">
            <a href="{{ route('catalog.index', $options) }}" @if($section === 'components') aria-current="page" @endif>Компоненты</a>
            <a href="{{ route('catalog.installation', $options) }}" @if($section === 'installation') aria-current="page" @endif>Подключение</a>
        </nav>
        <span class="wb-topbar-note">Локальный каталог</span>
    </header>
    <aside class="wb-sidebar">
        <a class="wb-catalog-home" href="{{ route('catalog.index', $options) }}" @if($current === null && $section === 'components') aria-current="page" @endif>Все компоненты</a>
        <nav aria-label="Каталог компонентов" class="wb-navigation">
            @foreach($groups as $group)
                @if(count($group['components']))
                    <div class="wb-nav-group">
                        <p class="wb-eyebrow">{{ $group['label'] }}</p>
                        @foreach($group['components'] as $item)
                            <a href="{{ route('catalog.show', ['component' => $item['id'], ...$options]) }}" @if($current === $item['id']) aria-current="page" @endif>
                                <span>{{ $item['displayName'] ?? $item['name'] }}</span><small>{{ $item['name'] }}</small>
                            </a>
                        @endforeach
                    </div>
                @endif
            @endforeach
        </nav>
        <div class="wb-sidebar-footer"><span>Компоненты — experimental</span><span>Пакет ещё не опубликован</span></div>
    </aside>
    <x-ui::page :title="$title" id="catalog-content" class="wb-main" tabindex="-1">
        <x-slot:description>{{ $description }}</x-slot:description>
        @isset($actions)
            <x-slot:actions>
                {{ $actions }}
            </x-slot:actions>
        @endisset
        <div class="wb-content">{{ $slot }}</div>
        <footer class="wb-meta">Каталог {{ $metadata['catalogVersion'] }} · {{ $metadata['libraryVersion'] ?? 'Локальная версия' }}</footer>
    </x-ui::page>
</x-ui::scope>
</body>
</html>
