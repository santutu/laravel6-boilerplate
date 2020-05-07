<?php


namespace App\Macros;


use Illuminate\Database\Query\Builder;

class QueryBuilderMacro implements IMacroRegistable
{

    public function regist()
    {

        Builder::macro('whereInByValue', function ($value, array $columns) {
            $strColumns = implode(',', $columns);
            $this->whereRaw("'{$value}' IN ($strColumns)");
        });

        Builder::macro('whereInMultiple', function (array $columns, array $values) {

            $values = array_map(function (array $value) {
                return "('" . implode($value, "', '") . "')";
            }, $values);

            return $this->whereRaw(
                '(' . implode($columns, ', ') . ') in (' . implode($values, ', ') . ')'
            );
        });
    }
}
