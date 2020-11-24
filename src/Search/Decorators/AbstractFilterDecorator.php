<?php

namespace Suhrr\LaravelSearcher\Search\Decorators;

use Illuminate\Support\Str;

abstract class AbstractFilterDecorator
{
    /**
     * @var string
     */
    protected $filter_namespace = '';

    /**
     * Checks if the filter has been defined
     *
     * @param string $name
     * @return boolean
     */
    public function filiterExists(string $name): bool
    {
        if (class_exists($this->createFilter($name))) {
            return true;
        }
        return false;
    }

    /**
     * create filter decorator
     *
     * @param string $name
     * @return string
     */
    protected function createFilter(string $name): string
    {
        return  $this->filter_namespace . Str::studly($name);
    }

    /**
     * リクエストされた値をDecorator用にバリデーションする
     *
     * @param $decorator
     * @param $value
     * @return boolean
     */
    protected function validate($filter, $value): bool
    {
        if (is_null($value)) {
            return false;
        }

        if (empty($value)) {
            return false;
        }

        if (!class_exists($filter)) {
            return false;
        }

        return true;
    }
}
