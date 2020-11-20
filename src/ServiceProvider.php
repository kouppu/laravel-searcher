<?php

namespace Suhrr\LaravelSearcher;

use Suhrr\LaravelSearcher\LaravelSearcher;

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
            'laravelSearcher',
            function () {
                return new LaravelSearcher();
            }
        );
    }

    public function provides()
    {
        return [
            'laravelSearcher',
        ];
    }
}
