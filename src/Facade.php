<?php
namespace Suhrr\LaravelSearchable;

/**
 * @method \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection search(Eloquent $model)
 *
 */
class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return LaravelSearchable::class;
    }
}
