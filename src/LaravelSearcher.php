<?php
namespace Suhrr\LaravelSearcher;

use Illuminate\Database\Eloquent\Model as Eloquent;

class LaravelSearcher
{
    /** @var string */
    private $root = '\\App\\Search';

    /**
     * search
     *
     * @param Eloquent $model
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function search(Eloquent $model)
    {
        $child_search = $this->createChildSearch($model);
        $search = new $child_search();
        return $search->search($model);
    }

    /**
     * reset
     *
     * @param Eloquent $model
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function reset(Eloquent $model)
    {
        $child_search = $this->createChildSearch($model);
        $search = new $child_search();
        return $search->reset($model);
    }

    /**
     * 継承先の検索クラスのインスタンス文字列を生成
     *
     * @param Eloquent $model
     * @return string
     */
    private function createChildSearch(Eloquent $model): string
    {
        $model_names = explode('\\', get_class($model));
        $model_name = end($model_names);
        return "{$this->root}\\{$model_name}\\{$model_name}Search";
    }
}
