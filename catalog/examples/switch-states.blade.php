<div class="wb-example-stack">
    <x-ui::switch name="digest">Еженедельный дайджест</x-ui::switch>
    <x-ui::switch name="notifications" :checked="true">Уведомления о событиях</x-ui::switch>
    <x-ui::switch :disabled="true">Недоступная настройка</x-ui::switch>
</div>
