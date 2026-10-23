<?php

declare(strict_types=1);

namespace Workbench\Catalog;

use Aestetic\Ui\AesteticUiServiceProvider;
use LogicException;
use ReflectionClass;

final class Catalog
{
    private readonly string $root;
    private readonly array $data;

    public function __construct()
    {
        $this->root = dirname((new ReflectionClass(AesteticUiServiceProvider::class))->getFileName(), 2);
        $this->data = json_decode(file_get_contents($this->root.'/catalog/components.json'), true, 512, JSON_THROW_ON_ERROR);
    }

    public function metadata(): array
    {
        return array_intersect_key($this->data, array_flip(['catalogVersion', 'libraryVersion', 'implementationStatus']));
    }

    public function components(): array
    {
        return $this->data['components'];
    }

    public function groups(): array
    {
        return array_map(fn (array $group) => [...$group,
            'components' => array_values(array_filter($this->components(), fn (array $component) => ($component['catalogGroup'] ?? null) === $group['id'])),
        ], $this->data['componentGroups']);
    }

    public function installation(): array
    {
        return $this->data['installation'];
    }

    public function packagePath(): string
    {
        return $this->root;
    }

    public function component(string $id): array
    {
        foreach ($this->components() as $component) {
            if ($component['id'] === $id) {
                return $component;
            }
        }

        abort(404);
    }

    public function canPreview(array $component): bool
    {
        return in_array($component['status'], ['experimental', 'implemented'], true)
            && isset($component['implementation'], $component['api']);
    }

    public function scenarios(array $component): array
    {
        if (! $this->canPreview($component)) {
            return [];
        }

        return array_map(function (array $scenario): array {
            if (! preg_match('/\A[a-z0-9]+(?:-[a-z0-9]+)*\z/', $scenario['id'])) {
                throw new LogicException('Invalid catalog scenario identifier.');
            }

            $path = $this->examplesPath().'/'.$scenario['id'].'.blade.php';

            if (! is_file($path)) {
                throw new LogicException('Missing catalog scenario: '.$scenario['id']);
            }

            return [...$scenario,
                'view' => 'catalog-examples::'.$scenario['id'],
                'source' => file_get_contents($path),
                'height' => max(120, min(800, (int) ($scenario['height'] ?? 160))),
            ];
        }, $component['previews'] ?? []);
    }

    public function scenario(array $component, string $id): array
    {
        foreach ($this->scenarios($component) as $scenario) {
            if ($scenario['id'] === $id) {
                return $scenario;
            }
        }

        abort(404);
    }

    public function examplesPath(): string
    {
        return $this->root.'/catalog/examples';
    }
}
