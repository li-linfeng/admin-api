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
    'namespace'  => 'App\Admin\Controllers',
    'middleware' => ['api'],
    'prefix'     => 'admin',
], function ($api) {

    /**
     * 无需登录的接口
     */
    // $api->post('/login', 'AdminUserController@login');


    /**
     * 需要登录的接口
     */

    $api->group([
        'middleware' => ['jwt.role:admin']
    ], function ($api) {
    });
});
