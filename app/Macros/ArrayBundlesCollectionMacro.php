<?php

namespace App\Macros;

use Illuminate\Support\Collection;

class ArrayBundlesCollectionMacro implements IMacroRegistable
{
    public function regist()
    {

        Collection::macro('bundles', function (callable $condition) {
            $items = $this->values();

            $results = collect([]);
            $bundle = collect([]);
            $itemsCount = $items->count();
            for ($i = 0; $i < $itemsCount; $i++) {
                $item = $items[$i];
                $beforeItem = $items[$i - 1] ?? null;

                if ($condition($item, $beforeItem, $bundle)) {
                    $bundle->push($item);
                } else {
                    $results->push($bundle);
                    $bundle = collect([$item]);
                }

                if ($i === $itemsCount - 1) $results->push($bundle);

            }

            return $results;
        });

        Collection::macro('arrayBundles', function (callable $condition) {
            $items = $this->values();

            $results = [];
            $bundle = [];
            $itemsCount = $items->count();
            for ($i = 0; $i < $itemsCount; $i++) {
                $item = $items[$i];
                $beforeItem = $items[$i - 1] ?? null;

                if ($condition($item, $beforeItem, $bundle)) {
                    $bundle[] = $item;
                } else {
                    array_push($results, $bundle);
                    $bundle = [$item];
                }

                if ($i === $itemsCount - 1) array_push($results, $bundle);

            }

            return $results;
        });
    }
}
