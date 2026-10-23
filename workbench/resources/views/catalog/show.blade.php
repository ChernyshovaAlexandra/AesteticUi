<x-catalog-layout :title="$selected['displayName'] ?? $selected['name']" :description="$selected['guidance']['summary'] ?? $selected['purpose']" :groups="$groups" :metadata="$metadata" :options="$options" :current="$selected['id']">
    <x-slot:actions><span class="wb-status">{{ $canPreview ? 'Экспериментальный' : 'Запланирован' }}</span></x-slot:actions>
    <nav class="wb-breadcrumbs" aria-label="Положение в каталоге">
        <a href="{{ route('catalog.index', $options) }}">Компоненты</a><x-ui::icon name="chevron-right" /><span aria-current="page">{{ $selected['displayName'] ?? $selected['name'] }}</span>
    </nav>
    @if($canPreview)
        <nav class="wb-page-links" aria-label="На этой странице"><a href="#guidance">Правила UI/UX</a><a href="#usage">Подключение</a><a href="#examples">Примеры и состояния</a><a href="#api">API</a></nav>
    @endif
    @isset($selected['guidance'])
        <div class="wb-guidance-grid" id="guidance">
            @foreach(['when' => 'Когда использовать', 'placement' => 'Где размещать', 'avoid' => 'Чего избегать', 'accessibility' => 'Подписи и клавиатура'] as $key => $heading)
                @if(! empty($selected['guidance'][$key]))
                    <section class="wb-guide-section"><h2>{{ $heading }}</h2><ul>@foreach($selected['guidance'][$key] as $rule)<li>{{ $rule }}</li>@endforeach</ul></section>
                @endif
            @endforeach
        </div>
    @endisset
    @if($canPreview)
        <section class="wb-guide-section" id="usage"><h2>Как добавить в интерфейс</h2><p>Внутри Scope, после подключения Assets в основном layout:</p><pre class="wb-code"><code>{{ $selected['api']['example'] }}</code></pre><x-ui::link :href="route('catalog.installation', $options)">Полная инструкция подключения в Laravel</x-ui::link></section>
    @endif
<section id="examples" class="wb-controls" aria-label="Настройки просмотра">
    @foreach ($controls as $name => $values)
        <div class="wb-control-group" role="group" aria-label="{{ ['theme' => 'Тема', 'density' => 'Плотность', 'viewport' => 'Ширина превью'][$name] }}">
            <span class="wb-control-label">{{ ['theme' => 'Тема', 'density' => 'Плотность', 'viewport' => 'Превью'][$name] }}</span>
            <div class="wb-segmented">
                @foreach ($values as $value => $label)
                    <a href="{{ route('catalog.show', ['component' => $selected['id'], ...$options, $name => $value]) }}" @if($options[$name] === $value) aria-current="true" @endif>{{ $label }}</a>
                @endforeach
            </div>
        </div>
    @endforeach
</section>

@if ($canPreview)
    <p class="wb-note">{{ $selected['api']['notes'] ?? '' }}</p>
    <section class="wb-scenarios" aria-label="Состояния компонента">
        @forelse ($scenarios as $scenario)
            <article class="wb-scenario" id="{{ $scenario['id'] }}">
                <header class="wb-scenario-heading">
                    <h2>{{ $scenario['title'] }}</h2>
                    <p>{{ $scenario['description'] }}</p>
                </header>
                <div class="wb-preview-stage wb-preview-stage--{{ $options['viewport'] }}">
                    <iframe src="{{ route('catalog.preview', ['component' => $selected['id'], 'scenario' => $scenario['id'], ...$options]) }}" title="{{ $selected['name'] }} — {{ $scenario['title'] }}" loading="eager" style="height: {{ $scenario['height'] }}px"></iframe>
                </div>
                <details class="wb-source">
                    <summary>Исходный Blade</summary>
                    <pre><code>{{ $scenario['source'] }}</code></pre>
                </details>
            </article>
        @empty
            <p class="wb-note">Для этого контракта пока не добавлены сценарии.</p>
        @endforelse
    </section>

    <section id="api" class="wb-api" aria-labelledby="api-title">
        <h2 id="api-title">API текущего контракта</h2>
        <p class="wb-note">{{ $selected['implementation']['path'] }}</p>
        @if (count($selected['api']['props']))
            <div class="wb-table-scroll">
                <table>
                    <caption class="wb-visually-hidden">Параметры {{ $selected['name'] }}</caption>
                    <thead><tr><th scope="col">Параметр</th><th scope="col">По умолчанию</th><th scope="col">Значения</th></tr></thead>
                    <tbody>
                    @foreach ($selected['api']['props'] as $prop => $definition)
                        <tr>
                        <th scope="row"><code>{{ $prop }}</code>@if($definition['required'] ?? false)<br><small>Обязательный</small>@endif</th>
                            <td><code>{{ json_encode($definition['default'], JSON_UNESCAPED_UNICODE) }}</code></td>
                            <td>{{ isset($definition['values']) ? implode(' / ', $definition['values']) : ($definition['type'] ?? '—') }}@isset($definition['fallback'])<br><code>{{ $definition['fallback'] }}</code>@endisset</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>Компонент не принимает props.</p>
        @endif
        <p>Слоты: {{ count($selected['api']['slots']) ? implode(', ', $selected['api']['slots']) : 'нет' }}.</p>
    </section>
@else
    <section class="wb-not-available">
        <h2>Компонент запланирован</h2>
        <p>Для этого семейства ещё нет доступного Blade-контракта. Демонстрации и параметры появятся вместе с реализацией.</p>
    </section>
@endif

@if (! empty($selected['usageRules']))
    <section class="wb-rules" aria-labelledby="rules-title">
        <h2 id="rules-title">Правила использования</h2>
        <ul>@foreach($selected['usageRules'] as $rule)<li>{{ $rule }}</li>@endforeach</ul>
    </section>
@endif
</x-catalog-layout>
