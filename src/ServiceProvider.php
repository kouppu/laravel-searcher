<?php

namespace Suhrr\LaravelSearchable;

use Suhrr\LaravelSearchable\LaravelSearchable;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'laravelSearchable',
            function () {
                return new LaravelSearchable();
            }
        );
    }

    public function provides()
    {
        return [
            'laravelSearchable',
        ];
    }
}
