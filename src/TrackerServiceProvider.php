<?php namespace FrenchFrogs\Tracker;

use Illuminate\Support\ServiceProvider;

class TrackerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes.php';
        $this->app->make('FrenchFrogs\Tracker\TrackerController');
    }
}
