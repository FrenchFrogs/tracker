<?php namespace FrenchFrogs\Tracker;

use Carbon\Carbon;
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
        $this->publishes([
            __DIR__.'/../database/migrations/create_tracking_table.php' => database_path('migrations/' . Carbon::now()->format('Y_m_d_His') . '_create_tracking_table.php'),
        ], 'migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/Http/routes.php';
    }
}
