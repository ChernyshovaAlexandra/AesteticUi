<x-catalog-layout title="Подключение в Laravel" description="Одна установка библиотеки, затем выбор нужных Blade-компонентов." section="installation" :groups="$groups" :metadata="$metadata" :options="$options">
    <p class="wb-note">{{ $installation['statusNote'] }}</p>
    <section class="wb-guide-section"><h2>1. Подключите локальный пакет</h2>
        <p>{{ $installation['prerequisites'] }} Добавьте эту запись в массив <code>repositories</code> файла <code>composer.json</code> вашего приложения, сохранив остальные настройки.</p>
        <pre class="wb-code"><code>{{ $repository }}</code></pre>
        <pre class="wb-code"><code>{{ implode("\n", $installation['commands']) }}</code></pre>
    </section>
    <section class="wb-guide-section"><h2>2. Добавьте ассеты и область оформления</h2>
        <p>Вызов Assets размещается в head основного layout. Scope задаёт тему и плотность для находящихся внутри компонентов.</p>
        <pre class="wb-code"><code>{{ $installation['layoutExample'] }}</code></pre>
    </section>
    <section class="wb-guide-section"><h2>3. Выберите компонент по задаче</h2>
        <p>Не копируйте оформление из каталога: используйте компонент пакета. Данные, маршруты, серверная валидация и права доступа остаются в приложении.</p>
        <x-ui::link :href="route('catalog.index', $options)">Перейти к компонентам и правилам размещения</x-ui::link>
    </section>
    <section class="wb-guide-section"><h2>После обновления библиотеки</h2>
        <p>Обновляйте код и public-ассеты вместе. Готовые CSS и JavaScript входят в пакет; настройка Tailwind или Node в приложении не требуется.</p>
        <pre class="wb-code"><code>{{ implode("\n", $installation['updateCommands']) }}</code></pre>
    </section>
</x-catalog-layout>
