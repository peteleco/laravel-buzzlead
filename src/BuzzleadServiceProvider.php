<?php

namespace Peteleco\Buzzlead;

use Illuminate\Support\ServiceProvider;

class BuzzleadServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/buzzlead.php' => config_path('buzzlead.php'),
        ], 'config');
        if (! class_exists('CreateBuzzleadTrackTable')) {
            $this->publishes([
                __DIR__ . '/../database/migrations/create_buzzlead_track_table.php.stub' => database_path('migrations/' . date('Y_m_d_His',
                        time()) . '_create_buzzlead_track_table.php'),
            ], 'migrations');
        }
//        $mediaClass = config('medialibrary.media_model');
//        $mediaClass::observe(new MediaObserver());
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/buzzlead.php', 'buzzlead');
    }
}