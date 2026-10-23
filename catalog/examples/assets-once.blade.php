<x-ui::assets />
<x-ui::assets />

<p>Оба вызова используют одни CSS и JavaScript. В head этого примера Assets уже вызван оболочкой; повторные вызовы не добавляют подключения.</p>
<p><code>{{ app(\Aestetic\Ui\Support\Assets::class)->stylesheetUrl() }}</code></p>
