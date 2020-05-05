<?php 

namespace Limanweb\EloquentLogger;

use Illuminate\Support\ServiceProvider as CommonServiceProvider;

class ServiceProvider extends CommonServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       $this->publishes([
          __DIR__.'/../../config' => config_path('limanweb'),
       ]);

       $this->loadMigrationsFrom(__DIR__.'/../../migrations');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}