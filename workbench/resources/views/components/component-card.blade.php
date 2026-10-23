@props(['entry', 'options'])
<a class="wb-component-card" href="{{ route('catalog.show', ['component' => $entry['id'], ...$options]) }}">
    <div class="wb-component-card__heading"><h3>{{ $entry['displayName'] ?? $entry['name'] }}</h3><code>{{ $entry['name'] }}</code></div>
    <p>{{ $entry['guidance']['summary'] ?? $entry['purpose'] }}</p>
    <span class="wb-component-card__footer">{{ $entry['status'] === 'planned' ? 'Запланировано' : 'Примеры и правила' }}<x-ui::icon name="chevron-right" /></span>
</a>
