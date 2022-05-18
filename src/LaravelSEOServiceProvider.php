<?php

namespace RalphJSmit\Laravel\SEO;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelSEOServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-seo')
            ->hasConfigFile()
            ->hasViews('seo')
            ->hasMigration('create_seo_table');
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(SEOManager::class);
    }
}
