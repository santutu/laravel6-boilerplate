<?php

namespace App\Providers;

use App\Macros\CollectionMacro;
use App\Macros\QueryBuilderMacro;
use App\Macros\EloquentBuilderMacro;
use App\Macros\IMacroRegistable;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{

    /**
     * @var IMacroRegistable[]
     */
    protected $macroRegistClasses = [
        CollectionMacro::class,
        QueryBuilderMacro::class,
        EloquentBuilderMacro::class,
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
