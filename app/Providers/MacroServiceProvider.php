<?php

namespace App\Providers;

use App\Macros\ArrayBundlesCollectionMacro;
use App\Macros\FluentQueryBuilderMacro;
use App\Macros\GeneratorEloquentBuilderMacro;
use App\Macros\IMacroRegistable;
use App\Macros\PaginateCollectionMacro;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{

    /**
     * @var IMacroRegistable[]
     */
    protected $macroRegistClasses = [
        ArrayBundlesCollectionMacro::class,
        FluentQueryBuilderMacro::class,
        GeneratorEloquentBuilderMacro::class,
        PaginateCollectionMacro::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        foreach ($this->macroRegistClasses as $macroRegistClass) {
            resolve($macroRegistClass)->regist();
        }
    }
}
