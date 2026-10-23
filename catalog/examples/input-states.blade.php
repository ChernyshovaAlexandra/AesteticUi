<div class="wb-example-stack">
    <label class="wb-demo-field">Имя<x-ui::input name="name" placeholder="Как к вам обращаться" /></label>
    <label class="wb-demo-field">Почта<x-ui::input type="email" name="email" value="example@example.com" /></label>
    <label class="wb-demo-field">Число, включая ноль<x-ui::input type="number" :value="0" readonly /></label>
    <label class="wb-demo-field">Недоступное поле<x-ui::input value="Недоступно" disabled /></label>
    <label class="wb-demo-field">Поле с ошибкой<x-ui::input value="Неполное значение" :invalid="true" aria-describedby="demo-input-error" /><small id="demo-input-error">Уточните значение этого поля.</small></label>
</div>
