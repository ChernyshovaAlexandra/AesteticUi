<?php

declare(strict_types=1);

namespace Aestetic\Ui\Mcp;

use RuntimeException;

final class CatalogError extends RuntimeException
{
    public function __construct(
        public readonly string $reason,
        string $message,
        public readonly array $details = [],
    ) {
        parent::__construct($message);
    }
}
