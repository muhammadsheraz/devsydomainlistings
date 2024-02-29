<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Actions\Domains\DomainCreator;
use App\Contracts\Actions\Users\CustomerCreator;
use App\Contracts\Actions\Users\UserAuthenticator;
use App\Support\Actions\Domains\DomainCreatorAction;
use App\Support\Actions\Users\CustomerCreatorAction;
use App\Support\Actions\Users\UserAuthenticatorAction;

class ActionServiceProvider extends ServiceProvider
{
    protected $contractActionMap = [
        DomainCreator::class => DomainCreatorAction::class,
        UserAuthenticator::class => UserAuthenticatorAction::class,
        CustomerCreator::class => CustomerCreatorAction::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        foreach ($this->contractActionMap as $contract => $action) {
            $this->app->bind($contract, $action);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
