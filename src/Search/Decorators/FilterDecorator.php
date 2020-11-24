<?php

namespace Suhrr\LaravelSearcher\Search\Decorators;

use Suhrr\LaravelSearcher\Search\Decorators\AbstractFilterDecorator;
use Illuminate\Database\Eloquent\Builder;

class FilterDecorator extends AbstractFilterDecorator
{
    /**
     * @var string
     */
    protected $filter_namespace = '\\' . __NAMESPACE__ . '\\Filters\\';

    /**
     * @var array
     */
    private $sort_operators = [
        'asc',
        'desc'
    ];

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
        if ($filter_name === 'orderBy') {
            $arr = $this->parseSort($value);
            if (is_null($arr)) {
                return $builder;
            }
            $column = $arr['column'];
            $value = $arr['value'];
        } else {
            $column = $param['column'];
        }

        $filter = $this->createFilter($filter_name);
        if ($this->validate($filter, $value)) {
            return $filter::apply($builder, $column, $value);
        }
        return $builder;
    }

    /**
     * ソート情報をフィルターに必要な形にパースする
     *
     * @param string $value
     * @return array|null
     */
    private function parseSort(string $value): ?array
    {
        foreach ($this->sort_operators as $operator) {
            $arr = explode("_{$operator}", $value);
            if (isset($arr[1])) {
                return [
                    'value' => $operator,
                    'column' => $arr[0]
                ];
            }
        }
        return null;
    }
}
