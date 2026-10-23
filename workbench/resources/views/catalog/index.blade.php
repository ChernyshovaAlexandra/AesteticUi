<x-catalog-layout title="Компоненты" description="Базовые элементы интерфейса: как выбрать, где разместить и как подключить в Laravel." :groups="$groups" :metadata="$metadata" :options="$options">
    <div class="wb-intro">
        <h2>Начните с атомарных элементов</h2>
        <p>Выберите элемент по задаче. Внутри — живые примеры, состояния, правила UI/UX и Blade-код для подключения.</p>
        <x-ui::link :href="route('catalog.installation', $options)">Как подключить библиотеку в Laravel</x-ui::link>
    </div>
    @foreach($groups as $group)
        @if(count($group['components']))
            <section class="wb-component-group" id="group-{{ $group['id'] }}" aria-labelledby="title-{{ $group['id'] }}">
                <header><h2 id="title-{{ $group['id'] }}">{{ $group['label'] }}</h2><p>{{ $group['description'] }}</p></header>
                <div class="wb-component-grid">
                    @foreach($group['components'] as $item)
                        <x-component-card :entry="$item" :options="$options" />
                    @endforeach
                </div>
            </section>
        @endif
    @endforeach
</x-catalog-layout>
