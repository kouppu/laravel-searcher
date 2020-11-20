<?php

namespace Suhrr\LaravelSearchable\Search;

use Illuminate\Database\Eloquent\Builder;

interface FilterInterface
{
    /**
     * Apply a given search value to the builder instance.
     *
     * @param Builder $builder
     * @param string $column
     * @param mixed $value
     *
     * @return Builder $builder
     */
    public static function apply(Builder $builder, string $column, $value): Builder;
}
