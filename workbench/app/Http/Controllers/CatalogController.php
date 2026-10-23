<?php

declare(strict_types=1);

namespace Workbench\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Workbench\Catalog\Catalog;

final class CatalogController
{
    private const CONTROLS = [
        'theme' => ['system' => 'Системная', 'light' => 'Светлая', 'dark' => 'Тёмная'],
        'density' => ['comfortable' => 'Обычная', 'compact' => 'Компактная'],
        'viewport' => ['wide' => 'Вся ширина', 'narrow' => '360 px'],
    ];

    public function __construct(private readonly Catalog $catalog) {}

    public function index(Request $request): View
    {
        return view('catalog.index', $this->pageData($request));
    }

    public function installation(Request $request): View
    {
        $installation = $this->catalog->installation();

        return view('catalog.installation', [...$this->pageData($request),
            'installation' => $installation,
            'repository' => json_encode(['type' => 'path', 'url' => $this->catalog->packagePath(),
                'options' => ['symlink' => true, 'versions' => [$installation['package'] => $installation['localVersion']]],
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
        ]);
    }

    private function pageData(Request $request): array
    {
        return [
            'groups' => $this->catalog->groups(),
            'metadata' => $this->catalog->metadata(),
            'options' => $this->options($request),
        ];
    }

    public function show(Request $request, string $component = 'button'): View
    {
        $selected = $this->catalog->component($component);

        return view('catalog.show', [...$this->pageData($request),
            'selected' => $selected,
            'components' => $this->catalog->components(),
            'canPreview' => $this->catalog->canPreview($selected),
            'scenarios' => $this->catalog->scenarios($selected),
            'metadata' => $this->catalog->metadata(),
            'controls' => self::CONTROLS,
            'options' => $this->options($request),
        ]);
    }

    public function preview(Request $request, string $component, string $scenario): View
    {
        $selected = $this->catalog->component($component);

        return view('catalog.preview', [
            'selected' => $selected,
            'scenario' => $this->catalog->scenario($selected, $scenario),
            'options' => $this->options($request),
        ]);
    }

    private function options(Request $request): array
    {
        $options = [];

        foreach (self::CONTROLS as $name => $values) {
            $value = $request->query($name, array_key_first($values));
            abort_unless(is_string($value) && array_key_exists($value, $values), 422, 'Недопустимые параметры просмотра.');
            $options[$name] = $value;
        }

        return $options;
    }
}
