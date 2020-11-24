<?php

namespace Suhrr\LaravelSearcher\Search;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Suhrr\LaravelSearcher\Search\Decorators\FilterDecorator;
use Suhrr\LaravelSearcher\Search\Decorators\ExtentionFilterDecorator;

abstract class AbstractSearch
{
    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var FilterDecorator
     */
    private $filterDecorator;

    /**
     * @var ExtentionFilterDecorator
     */
    private $extFilterDecorator;

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

    public function __construct()
    {
        $this->filterDecorator = new FilterDecorator();
        $this->extFilterDecorator = new ExtentionFilterDecorator();
    }

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
    private function applyDecoratorsFromRequest(Request $request, Builder $builder)
    {
        foreach ($request->all() as $name => $value) {
            $index = array_search($name, array_column($this->params, 'name'));
            if ($index === false) {
                continue;
            }

            $param = $this->params[$index];
            $filter_name = $this->convertTypeToName($param['type']);

            if ($this->filterDecorator->filiterExists($filter_name)) {
                $builder = $this->filterDecorator->decorte($builder, $param, $value, $filter_name);
            }

            // Set extention filters
            $this->extFilterDecorator->setFilterNamespace($builder);
            if ($this->extFilterDecorator->filiterExists($filter_name)) {
                $builder = $this->extFilterDecorator->decorte($builder, $param, $value, $filter_name);
            }
        }
        return $builder;
    }

    /**
    * convaert type to filter name
    *
    * @param string $type
    * @return string|null
    */
    protected function convertTypeToName(string $type): ?string
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
                return $type;
        }
    }
}
