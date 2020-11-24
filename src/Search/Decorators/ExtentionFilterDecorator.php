<?php

namespace Suhrr\LaravelSearcher\Search\Decorators;

use Suhrr\LaravelSearcher\Search\Decorators\AbstractFilterDecorator;
use Suhrr\LaravelSearcher\LaravelSearcher;
use Illuminate\Database\Eloquent\Builder;

class ExtentionFilterDecorator extends AbstractFilterDecorator
{
    /**
     * Set target filiter namespace
     *
     * @param Builder $builder
     * @return void
     */
    public function setFilterNamespace(Builder $builder): void
    {
        $model_name = class_basename($builder->getModel());
        $this->filter_namespace = app(LaravelSearcher::class)->getRootNamespace() . '\\' . $model_name . '\\Filiters\\';
    }

    /**
     * フィルターをビルダーにセットしていく
     *
     * @param Builder $builder
     * @param array $param
     * @param mixed $value
     * @param string $filter_name
     * @return Builder
     */
    public function decorte(Builder $builder, array $param, $value, string $filter_name): Builder
    {
        $column = $param['column'];
        $filter = $this->createFilter($filter_name);
        if ($this->validate($filter, $value)) {
            return $filter::apply($builder, $column, $value);
        }
        return $builder;
    }
}
