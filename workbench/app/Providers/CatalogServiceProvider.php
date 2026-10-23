<?php

declare(strict_types=1);

namespace Workbench\Providers;

use Illuminate\Support\ServiceProvider;
use Workbench\Catalog\Catalog;

final class CatalogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Catalog::class);
    }

    public function boot(Catalog $catalog): void
    {
        $this->loadViewsFrom($catalog->examplesPath(), 'catalog-examples');
    }
}
