@once('aestetic-ui-assets')
    <link rel="stylesheet" href="{{ app(\Aestetic\Ui\Support\Assets::class)->stylesheetUrl() }}">
    <script type="module" data-aui-runtime src="{{ app(\Aestetic\Ui\Support\Assets::class)->scriptUrl() }}"></script>
@endonce
