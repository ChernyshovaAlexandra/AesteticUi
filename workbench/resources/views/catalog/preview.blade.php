<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $selected['name'] }}: {{ $scenario['title'] }}</title>
    <x-ui::assets />
    <link rel="stylesheet" href="{{ asset('catalog.css') }}">
</head>
<body class="wb-preview-document">
    <x-ui::scope :theme="$options['theme']" :density="$options['density']" class="wb-preview-scope">
        <div class="wb-preview-content">
            @include($scenario['view'], ['theme' => $options['theme'], 'density' => $options['density']])
        </div>
    </x-ui::scope>
</body>
</html>
