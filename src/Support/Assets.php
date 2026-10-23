<?php

declare(strict_types=1);

namespace Aestetic\Ui\Support;

use Illuminate\Contracts\Routing\UrlGenerator;
use RuntimeException;

final class Assets
{
    public function __construct(
        private readonly UrlGenerator $urls,
        private readonly string $manifestPath,
    ) {}

    public function stylesheetUrl(): string
    {
        return $this->url('css');
    }

    public function scriptUrl(): string
    {
        return $this->url('js');
    }

    private function url(string $entry): string
    {
        $contents = file_get_contents($this->manifestPath);

        if ($contents === false) {
            throw new RuntimeException('Aestetic UI asset manifest is missing. Reinstall the package.');
        }

        $manifest = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        $file = $manifest['assets'][$entry]['file'];

        return $this->urls->asset('vendor/aestetic-ui/'.$file);
    }
}
