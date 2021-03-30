<?php
namespace Suhrr\LaravelSearcher;

use Illuminate\Database\Eloquent\Model as Eloquent;

class LaravelSearcher
{
    /** @var string */
    private $root_namespace = '';

    public function __construct()
    {
        $this->root_namespace = \App::getNamespace() . 'Searches';
    }

    public function getRootNamespace(): string
    {
        return $this->root_namespace;
    }

    /**
     * Search
     *
     * @param Eloquent $model
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function search(Eloquent $model)
    {
        $search = $this->createChildSearch($model);
        return $search->search($model);
    }

    /**
     * Reset
     *
     * @param Eloquent $model
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function reset(Eloquent $model)
    {
        $search = $this->createChildSearch($model);
        return $search->reset($model);
    }

    /**
     * Create an instance string of the search class
     *
     * @param Eloquent $model
     * @return object
     */
    private function createChildSearch(Eloquent $model): object
    {
        $model_name = class_basename($model);
        $namespace = "{$this->root_namespace}\\{$model_name}\\{$model_name}Search";
        return new $namespace();
    }
}
