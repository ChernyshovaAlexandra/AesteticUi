<div class="wb-example-stack" role="group" aria-label="Способ уведомления">
    <x-ui::radio name="notification" value="email" :checked="true">По электронной почте</x-ui::radio>
    <x-ui::radio name="notification" value="sms">По SMS</x-ui::radio>
    <x-ui::radio name="notification" value="push" :disabled="true">Push пока недоступен</x-ui::radio>
</div>
