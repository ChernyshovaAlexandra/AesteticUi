<div class="wb-example-stack">
    <label class="wb-demo-field">Изображения<x-ui::file-input name="images[]" accept="image/*" :multiple="true" /><small>Выбор файлов с устройства. Отправка на сервер здесь не выполняется.</small></label>
    <label class="wb-demo-field">Недоступно<x-ui::file-input :disabled="true" /></label>
</div>
