<x-ui::scope theme="light" class="wb-scope-sample">
    <p>Светлая область</p>
    <x-ui::button variant="outline">Светлая кнопка</x-ui::button>
    <x-ui::scope theme="dark" density="compact" class="wb-scope-sample">
        <p>Вложенная тёмная область с компактной плотностью</p>
        <x-ui::button>Тёмная кнопка</x-ui::button>
    </x-ui::scope>
</x-ui::scope>
