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

    $api->get('/test', 'TestController@test')->name('api.test.test');
    $api->post('/login', 'AuthController@login')->name('api.login.login');
    $api->get('/permissions', 'permissionController@permissions')->name('api.permission.info');
    /**
     * 无需登录的接口
     */


    /**
     * 需要登录的接口
     */

    $api->group([
        'middleware' => ['auth.jwt']

    ], function ($api) {
        $api->get('/user/info', 'UserController@info')->name('api.user.info')->permissions('用户信息:用户信息');

        $api->get('/categories', 'CategoryController@list')->name('api.categories.list')->permissions("分类管理:分类列表");
        $api->post('/categories', 'CategoryController@store')->name('api.categories.store')->permissions("分类管理:新增分类");
        $api->delete('/categories/{category}', 'CategoryController@delete')->name('api.categories.delete')->permissions("分类管理:删除分类");


        $api->get('/projects', 'ProjectController@list')->name('api.projects.list')->permissions("项目管理:项目列表");
        $api->post('/projects', 'ProjectController@store')->name('api.projects.store')->permissions("项目管理:新增项目");
        $api->patch('/projects/{project}/status', 'ProjectController@status')->name('api.projects.status')->permissions("项目管理:更新项目状态");
        $api->delete('/projects/{project}', 'ProjectController@delete')->name('api.projects.delete')->permissions("项目管理:删除项目");



        $api->get('/sale_requests', 'SaleRequestController@list')->name('api.sale_requests.list')->permissions("销售需求:销售需求列表");
        $api->get('/sale_request_num', 'SaleRequestController@getUniqueId')->name('api.sale_requests.uuid')->permissions("销售需求:获取销售需求编码");
        $api->post('/sale_requests', 'SaleRequestController@store')->name('api.sale_requests.store')->permissions("销售需求:新增销售需求");
        $api->put('/sale_requests/{request}', 'SaleRequestController@update')->name('api.sale_requests.update')->permissions("销售需求:编辑销售需求");
        $api->delete('/sale_requests/{request}', 'SaleRequestController@delete')->name('api.sale_requests.delete')->permissions("销售需求:删除销售需求");
        $api->post('/sale_requests/{request}/handle_user', 'SaleRequestController@dispatchHandler')->name('api.sale_requests.handle_user')->permissions("销售需求:指定销售需求处理人");


        $api->get('/preSales', 'PreSaleController@list')->name('api.preSales.list')->permissions("工程售前:工程售前列表");
        $api->put('/preSales/{request}', 'PreSaleController@update')->name('api.preSales.update')->permissions("工程售前:添加工程售前资料");

        $api->get('/order_num', 'OrderController@getOrderNum')->name('api.order.order_num')->permissions("订单:获取订单编码");
        $api->post('/orders', 'OrderController@store')->name('api.order.store')->permissions("订单:新增订单");
        $api->get('/orders', 'OrderController@list')->name('api.order.list')->permissions("订单:订单列表");

        $api->post('/upload', 'UploadController@upload')->name('api.upload.upload')->permissions("附件上传:附件上传");
    });
});
