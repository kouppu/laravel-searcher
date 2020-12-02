<?php

namespace Suhrr\LaravelSearcher;

use Suhrr\LaravelSearcher\LaravelSearcher;
use Suhrr\LaravelSearcher\Console\MakeSearchCommand;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'searcher',
            function () {
                return new LaravelSearcher();
            }
        );
        $this->commands([
            MakeSearchCommand::class
        ]);
    }

    public function provides()
    {
        return [
            'searcher',
        ];
    }
}
