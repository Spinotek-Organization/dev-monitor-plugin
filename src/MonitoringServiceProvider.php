<?php

namespace Spinotek\TaskMonitoring;

use Illuminate\Support\ServiceProvider;

class MonitoringServiceProvider extends ServiceProvider
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
        // Load Web and API routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        // Load Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Load Views under namespace 'task-monitoring'
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'task-monitoring');

        // Publish Views if user wants to customize them
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/task-monitoring'),
            ], 'task-monitoring-views');

            $this->publishes([
                __DIR__ . '/../data' => storage_path('task-monitoring'),
            ], 'task-monitoring-data');
        }
    }
}
