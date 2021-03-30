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
     * Create filter decorator
     *
     * @param string $name
     * @return string
     */
    protected function createFilter(string $name): string
    {
        return  $this->filter_namespace . Str::studly($name);
    }

    /**
     * Validate the requested value
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
