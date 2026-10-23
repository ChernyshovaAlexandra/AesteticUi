<?php

declare(strict_types=1);

namespace Aestetic\Ui;

use Aestetic\Ui\Support\Assets;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

final class AesteticUiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/aestetic-ui.php', 'aestetic-ui');

        $this->app->singleton(Assets::class, fn ($app) => new Assets(
            $app->make(UrlGenerator::class),
            __DIR__.'/../dist/manifest.json',
        ));
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ui');
        Blade::anonymousComponentPath(__DIR__.'/../resources/views/components', 'ui');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../dist' => public_path('vendor/aestetic-ui'),
            ], 'aestetic-ui-assets');

            $this->publishes([
                __DIR__.'/../config/aestetic-ui.php' => config_path('aestetic-ui.php'),
            ], 'aestetic-ui-config');
        }
    }
}
