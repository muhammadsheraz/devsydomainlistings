<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Actions\Users\CustomerCreator;
use App\Services\CustomerCreatorService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CustomerCreator::class, CustomerCreatorService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
