<div class="wb-example-stack">
    <div class="wb-demo-field"><label for="demo-city">Город</label><x-ui::select id="demo-city" name="city" :options="[['value' => 'hanoi', 'label' => 'Ханой'], ['value' => 'danang', 'label' => 'Дананг'], ['value' => 'other', 'label' => 'Другой город', 'disabled' => true]]" /></div>
    <div class="wb-demo-field"><label for="demo-size">Размер страницы</label><x-ui::select id="demo-size" name="size" :value="20" :placeholder="null" :options="[['value' => 10, 'label' => '10 записей'], ['value' => 20, 'label' => '20 записей']]" /></div>
    <div class="wb-demo-field"><label for="demo-select-disabled">Недоступный выбор</label><x-ui::select id="demo-select-disabled" :disabled="true" :options="[]" /></div>
</div>
