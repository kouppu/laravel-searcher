<?php

namespace Suhrr\LaravelSearcher\Console;

use Illuminate\Console\GeneratorCommand;

class MakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:search';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new search class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Search';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/search.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Searches';
    }
}
