<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon; //use tambahan

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         //dibawah ini adalah tambahan code
         config(['app.locale' => 'id']);
         Carbon::setLocale('id');
         date_default_timezone_set('Asia/Jakarta');
    }
}