<?php

namespace Suhrr\LaravelSearchable\Search\Filters;

use Suhrr\LaravelSearchable\Search\FilterInterface;
use Illuminate\Database\Eloquent\Builder;

class GreaterEqual implements FilterInterface
{
    /** @var string */
    private static $operator = '>=';

    public static function apply(Builder $builder, string $column, $value): Builder
    {
        return $builder->where($column, self::$operator, $value);
    }
}
