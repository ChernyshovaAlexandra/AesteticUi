<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aestetic UI</title>
    <x-ui::assets />
</head>
<body>
    <x-ui::scope>
        <x-ui::page title="Aestetic UI">
            <x-slot:description>Первый пример подключения библиотеки в Laravel.</x-slot:description>
            <x-slot:actions>
                <x-ui::button variant="outline" :disabled="true">Недоступно</x-ui::button>
            </x-slot:actions>

            <form method="GET" action="{{ url()->current() }}">
                <x-ui::button type="submit">Обновить страницу</x-ui::button>
            </form>
        </x-ui::page>
    </x-ui::scope>
</body>
</html>
