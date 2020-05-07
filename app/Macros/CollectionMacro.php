<?php

namespace App\Macros;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class CollectionMacro implements IMacroRegistable
{
    public function regist()
    {

        Collection::macro('paginate', function ($perPage = 15, $page = null, $options = []) {
            $items = $this;
            $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
            $items = $items instanceof Collection ? $items : Collection::make($items);
            return new LengthAwarePaginator($items->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, $options);
        });


        Collection::macro('chunkByCondition', function (callable $condition) {
            $items = $this->values();

            $results = collect([]);
            $bundle = [];
            $itemsCount = $items->count();
            for ($i = 0; $i < $itemsCount; $i++) {
                $item = $items[$i];
                $beforeItem = $items[$i - 1] ?? null;

                if ($condition($item, $beforeItem, $bundle)) {
                    $bundle[] = $item;
                } else {
                    $results->push($bundle);
                    $bundle = [$item];
                }

                if ($i === $itemsCount - 1) $results->push($bundle);;

            }

            return $results;
        });
    }
}
