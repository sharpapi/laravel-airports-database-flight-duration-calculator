<?php

declare(strict_types=1);

namespace SharpAPI\AirportsDatabaseFlightDurationCalculator;

use Illuminate\Support\ServiceProvider;

/**
 * @api
 */
class AirportsDatabaseFlightDurationCalculatorProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/sharpapi-airports-database-flight-duration-calculator.php' => config_path('sharpapi-airports-database-flight-duration-calculator.php'),
            ], 'sharpapi-airports-database-flight-duration-calculator');
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Merge the package configuration with the app configuration.
        $this->mergeConfigFrom(
            __DIR__.'/../config/sharpapi-airports-database-flight-duration-calculator.php', 'sharpapi-airports-database-flight-duration-calculator'
        );
    }
}