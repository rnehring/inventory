<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use romanzipp\ModelDoc\Services\DocumentationGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        DocumentationGenerator::usePath(fn () => base_path('app/Models'));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
