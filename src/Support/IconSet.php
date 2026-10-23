<?php

declare(strict_types=1);

namespace Aestetic\Ui\Support;

use InvalidArgumentException;

final class IconSet
{
    private static ?array $icons = null;

    public function svg(string $name): string
    {
        self::$icons ??= json_decode(file_get_contents(__DIR__.'/../../resources/icons/icons.json'), true, 512, JSON_THROW_ON_ERROR);

        if (! isset(self::$icons[$name])) {
            throw new InvalidArgumentException('Unknown Lucide icon: '.$name);
        }

        return preg_replace('/<svg\s/', '<svg aria-hidden="true" focusable="false" ', self::$icons[$name], 1);
    }
}
