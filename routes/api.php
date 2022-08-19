<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');


$api->version('v1', [
    'namespace'  => 'App\Http\Controllers',
    'middleware' => ['api'],
    'prefix'     => 'api',
], function ($api) {


    $api->get('/ping', function () {
        return response()->json(['message' => 'ping']);
    });

    $api->get('/test', 'TestController@test');
    $api->post('/login', 'AuthController@login');
    /**
     * 无需登录的接口
     */





    /**
     * 需要登录的接口
     */

    $api->group([
        'middleware' => ['auth.jwt']

    ], function ($api) {
        $api->get('/user/info', 'UserController@info')->name('api.user.info');

        $api->get('/categories', 'CategoryController@list')->name('api.categories.list');
        $api->post('/categories', 'CategoryController@store')->name('api.categories.store');
        $api->delete('/categories/{category}', 'CategoryController@delete')->name('api.categories.delete');


        $api->get('/projects', 'ProjectController@list')->name('api.projects.list');
        $api->post('/projects', 'ProjectController@store')->name('api.projects.store');
        $api->patch('/projects/{project}/status', 'ProjectController@status')->name('api.projects.status');
        $api->delete('/projects/{project}', 'ProjectController@delete')->name('api.projects.delete');



        $api->get('/sale_requests', 'SaleRequestController@list')->name('api.sale_requests.list');
        $api->post('/sale_requests', 'SaleRequestController@store')->name('api.sale_requests.store');
        $api->put('/sale_requests/{request}', 'SaleRequestController@update')->name('api.sale_requests.update');
        $api->delete('/sale_requests/{request}', 'SaleRequestController@delete')->name('api.sale_requests.delete');

        $api->post('/upload', 'UploadController@upload')->name('api.upload.upload');
    });
});
