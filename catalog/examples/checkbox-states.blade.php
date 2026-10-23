<div class="wb-example-stack">
    <x-ui::checkbox name="updates">Получать обновления</x-ui::checkbox>
    <x-ui::checkbox name="email" :checked="true">Письма по почте</x-ui::checkbox>
    <x-ui::checkbox name="all" :indeterminate="true">Выбрана часть записей</x-ui::checkbox>
    <x-ui::checkbox :disabled="true">Недоступная опция</x-ui::checkbox>
</div>
