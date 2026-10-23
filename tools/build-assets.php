<?php

declare(strict_types=1);

// Packaging only: no minifier, bundler or consumer-side Node dependencies.
$root = dirname(__DIR__);
$manifest = ['schemaVersion' => 1, 'assets' => []];

if (! is_dir($root.'/dist') && ! mkdir($root.'/dist', 0777, true)) {
    throw new RuntimeException('Unable to create Aestetic UI dist directory.');
}

foreach (['css', 'js'] as $extension) {
    $content = file_get_contents($root.'/resources/'.$extension.'/aestetic-ui.'.$extension);

    if ($content === false) {
        throw new RuntimeException('Unable to read Aestetic UI '.$extension.' source.');
    }

    $hash = hash('sha256', $content);
    $filename = 'aestetic-ui.'.substr($hash, 0, 12).'.'.$extension;

    if (file_put_contents($root.'/dist/'.$filename, $content) === false) {
        throw new RuntimeException('Unable to write Aestetic UI '.$extension.' asset.');
    }

    $manifest['assets'][$extension] = ['file' => $filename, 'sha256' => $hash];
}

$json = json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR).PHP_EOL;
$temporary = $root.'/dist/manifest.json.tmp';

if (file_put_contents($temporary, $json) === false || ! rename($temporary, $root.'/dist/manifest.json')) {
    throw new RuntimeException('Unable to write Aestetic UI asset manifest.');
}
