<?php 

namespace Limanweb\EloquentLogger

use Illuminate\Support\ServiceProvider;
use Limanweb\EloquentLogger\Services\EloquentLoggerService;

class EloquentLoggerProvider extends ServiceProvider 
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/publish/config' => config_path('limanweb/eloquent_logger'),
        ]);
        
        $this->loadMigrationsFrom(__DIR__.'/publish/migrations');
    }
    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(EloquentLoggerService::class, function() {
            return new EloquentLoggerService;
        });
    }
}