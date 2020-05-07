<?php


namespace App\Macros;


use Illuminate\Database\Eloquent\Builder;

class GeneratorEloquentBuilderMacro implements IMacroRegistable
{

    public function regist()
    {
        \Illuminate\Database\Eloquent\Builder::macro('generateAll', function () {
            /**
             * @var $model Builder
             */
            $model = $this->getModel();
            $currentId = 1;
            while (true) {
                $models = $model->limit(100)->orderBy('id')->where('id', '>=', $currentId)->cursor();
                if ($models->count() === 0) {
                    return;
                }
                foreach ($models as $instance) {
                    yield $instance;
                }
                $currentId = $models->last()->id + 1;
            }
        });


        Builder::macro('generateAllDesc', function () {
            /**
             * @var $model Builder
             */
            $model = $this->getModel();
            $lastModel = $model->orderByDesc('id')->first();
            if (!$lastModel) {
                return;
            }
            $currentId = $lastModel->id;
            while (true) {
                $models = $model->limit(100)->orderByDesc('id')->where('id', '<=', $currentId)->cursor();
                if ($models->count() === 0) {
                    return;
                }
                foreach ($models as $instance) {
                    yield $instance;
                }
                $currentId = $models->last()->id - 1;
            }
        });

        Builder::macro('updateWithAllGenerator', function (array $update, callable $cb) {
            /**
             * @var $model Builder
             */
            $model = $this->getModel();
            $lastModel = $model->orderByDesc('id')->first();
            if (!$lastModel) {
                return;
            }
            $currentId = $lastModel->id;
            $interval = 100000;
            while (true) {
                $updatedCount = $model->limit($interval)->orderByDesc('id')->where('id', '<=', $currentId)
                    ->update($update);

                $lastModel = $model->offset($interval)->orderByDesc('id')->where('id', '<=', $currentId)->first();

                $cb($updatedCount, $currentId, $interval);
                if (!$lastModel) {
                    return;
                }
                $currentId = $lastModel->id;
            }
        });


    }
}
