<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Modules\Customer\Models\Customer;
use Modules\Delegate\Models\Delegate;
use Modules\Setting\Models\Setting;
use Modules\Supplier\Models\Supplier;
use Modules\User\Models\User;
use Modules\Purchase\Models\Purchase;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict(!$this->app->isProduction());
        Relation::morphMap([
            'User'              => User::class,
            'Customer'          => Customer::class,
            'Supplier'          => Supplier::class,
            'Delegate'          => Delegate::class,
            'Setting'           => Setting::class,
            'Purchase'           => Purchase::class,
        ]);
    }
}
