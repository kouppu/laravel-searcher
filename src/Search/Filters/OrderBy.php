<?php

namespace Suhrr\LaravelSearcher\Search\Filters;

use Suhrr\LaravelSearcher\Search\FilterInterface;
use Illuminate\Database\Eloquent\Builder;

class OrderBy implements FilterInterface
{
    public static function apply(Builder $builder, string $column, $value): Builder
    {
        return $builder->orderBy($column, $value);
    }
}
