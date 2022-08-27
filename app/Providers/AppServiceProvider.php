<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {


        app('Dingo\Api\Exception\Handler')->register(function (\Exception $e) {
            // dd($e);
            if ($e instanceof \Dingo\Api\Exception\ValidationHttpException) {
                throw new HttpException(422, $e->getErrors()->first());
            }

            if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                if ($e->getPrevious()) {
                    $model = $e->getPrevious()->getModel();
                    $modelName = app($model)->modelName ?: '資源';
                    $message = '找不到該' . $modelName;
                    throw new HttpException(404, $message);
                }
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //注册模型观察者
        $this->registerObservers();
        Schema::defaultStringLength(191);
    }


    protected function registerObservers()
    {
    }
}
