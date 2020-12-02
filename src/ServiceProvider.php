<?php

namespace Suhrr\LaravelSearcher;

use Suhrr\LaravelSearcher\LaravelSearcher;
use Suhrr\LaravelSearcher\Console\MakeSearchCommand;
use Suhrr\LaravelSearcher\Console\MakeFilterCommand;

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
            MakeSearchCommand::class,
            MakeFilterCommand::class
        ]);
    }

    public function provides()
    {
        return [
            'searcher',
        ];
    }
}
