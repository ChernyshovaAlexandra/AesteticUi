@props(['title' => null])

<main {{ $attributes->class(['aui-page']) }}>
    @if ($title !== null || isset($description) || isset($actions))
        <header class="aui-page__header">
            <div class="aui-page__intro">
                @if ($title !== null)
                    <h1 class="aui-page__title">{{ $title }}</h1>
                @endif
                @isset($description)
                    <div class="aui-page__description">{{ $description }}</div>
                @endisset
            </div>
            @isset($actions)
                <div class="aui-page__actions">{{ $actions }}</div>
            @endisset
        </header>
    @endif
    <div class="aui-page__content">{{ $slot }}</div>
</main>
