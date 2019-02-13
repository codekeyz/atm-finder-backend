<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
        $this->app->register(\Tymon\JWTAuth\Providers\LumenServiceProvider::class);
        try {
            Cashier::useCurrency('ghc', '¢');
        } catch (\Exception $e) {
            dd($e);
        }
    }

}
