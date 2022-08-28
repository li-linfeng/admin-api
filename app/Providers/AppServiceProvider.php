<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Routing\Route;

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

        $this->registerRouteMacros();
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


    //注册理由方法
    public function registerRouteMacros()
    {
        if (!Route::hasMacro('menu')) {
            Route::macro('menu', function ($name) {
                $this->action['menu'] = $name;
                return $this;
            });
        }

        if (!Route::hasMacro('getMenu')) {
            Route::macro('getMenu', function () {
                return $this->action['menu'] ?? '';
            });
        }

        if (!Route::hasMacro('permissions')) {
            Route::macro('permissions', function ($name) {
                $this->action['permissions'] = $name;
                return $this;
            });
        }
        if (!Route::hasMacro('getPermission')) {
            Route::macro('getPermission', function () {
                return $this->action['permissions'] ?? '';
            });
        }
    }
}
