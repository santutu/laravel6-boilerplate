<?php


namespace App\Macros;


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class PaginateCollectionMacro implements IMacroRegistable
{

    public function regist()
    {
        Collection::macro('paginate', function ($perPage = 15, $page = null, $options = []) {
            $items = $this;
            $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
            $items = $items instanceof Collection ? $items : Collection::make($items);
            return new LengthAwarePaginator($items->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, $options);
        });
    }
}
