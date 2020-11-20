<?php

namespace Suhrr\LaravelSearcher\Search;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

abstract class AbstractSearch
{
    /**
     * @var Builder
     */
    protected $builder;

    /**
     * setting params
     *
     * @var array
     */
    protected $params;

    /**
     * use paginate flag
     *
     * @var boolean
     */
    protected $isPaginate = false;

    /**
     * default page num
     *
     * @var integer
     */
    protected $perPage = 10;

    /**
     * set builder
     *
     * @param Eloquent $model
     * @return void
     */
    protected function setBuilder(Eloquent $model)
    {
        $this->builder = $model->newQuery();
    }

    /**
     * 検索結果を取得
     *
     * @param Eloquent $model
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function search(Eloquent $model)
    {
        $this->setBuilder($model);
        $request = request();
        $request->flash();

        $this->builder = $this->applyDecoratorsFromRequest($request, $this->builder);
        if ($this->isPaginate) {
            return $this->builder->paginate($this->perPage)->appends($request->query());
        }

        return $this->builder->get();
    }

    /**
     * 検索リセット結果を取得
     *
     * @param Eloquent $model
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function reset(Eloquent $model)
    {
        $this->setBuilder($model);
        request()->flush();

        if ($this->isPaginate) {
            return $this->builder->paginate($this->perPage);
        }
        return $this->builder->get();
    }

    /**
     * BuilderにFiltersディレクリ配下のFilterをセットしていく
     *
     * @param Request $request
     * @param Builder $builder
     * @return Builder
     */
    private function applyDecoratorsFromRequest(Request $request, Builder $builder): Builder
    {
        foreach ($request->all() as $name => $value) {
            $index = array_search($name, array_column($this->params, 'name'));
            if ($index === false) {
                continue;
            }

            $param = $this->params[$index];
            $filter_name = $this->convertTypeToName($param['type']);
            if (is_null($filter_name)) {
                continue;
            }

            if ($filter_name === 'orderBy') {
                $arr = $this->parseSort($value);
                if (is_null($arr)) {
                    continue;
                }
                $column = $arr['column'];
                $value = $arr['value'];
            } else {
                $column = $param['column'];
            }

            $decorator = $this->createFilterDecorator($filter_name);
            if ($this->isValidDecorator($decorator, $value)) {
                $builder = $decorator::apply($builder, $column, $value);
            }
        }
        return $builder;
    }

    /**
     * create filter decorator
     *
     * @param string $name
     * @return string
     */
    private function createFilterDecorator(string $name): string
    {
        return  '\\' . __NAMESPACE__ . '\\Filters\\' . Str::studly($name);
    }

    /**
     * ソート情報をフィルターに必要な形にパースする
     *
     * @param string $value
     * @return array|null
     */
    private function parseSort(string $value): ?array
    {
        $arr = explode('_asc', $value);
        if (isset($arr[1])) {
            return [
                'value' => 'asc',
                'column' => $arr[0]
            ];
        }
        $arr = explode('_desc', $value);
        if (isset($arr[1])) {
            return [
                'value' => 'desc',
                'column' => $arr[0]
            ];
        }
        return null;
    }

    /**
     * リクエストされた値をDecorator用にバリデーションする
     *
     * @param $decorator
     * @param $value
     * @return boolean
     */
    private function isValidDecorator($decorator, $value): bool
    {
        if (is_null($value)) {
            return false;
        }

        if (empty($value)) {
            return false;
        }

        if (!class_exists($decorator)) {
            return false;
        }

        return true;
    }

    /**
     * convaert type to filter name
     *
     * @param string $type
     * @return string|null
     */
    private function convertTypeToName(string $type): ?string
    {
        switch ($type) {
            case '=':
                return 'equal';
                break;
            case '!=':
                return 'unequal';
                break;
            case '>':
                return 'greater';
                break;
            case '<':
                return 'less';
                break;
            case '>=':
                return 'greaterEqual';
                break;
            case '<=':
                return 'lessEqual';
                break;
            case 'like':
                return 'like';
                break;
            case 'orderBy':
                return 'orderBy';
                break;
            default:
                return null;
        }
    }

    /**
     * __callを使用し、
     * Builderにビルダーメソッドをセットする。
     *
     * @param string $method
     * @param array $arguments
     * @return void
     */
    public function __call(string $method, array $arguments): void
    {
        $this->builder->$method(...$arguments);
    }
}
