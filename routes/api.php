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
        $api->get('/user_permissions', 'UserController@getUserPermissions')->name('api.user.user_permission')->permissions('用户信息:用户权限');

        $api->get('/materials', 'MaterialController@index')->name('api.materials.index')->permissions("物料管理:物料列表");
        $api->post('/materials', 'MaterialController@store')->name('api.materials.store')->permissions("物料管理:新增物料");
        $api->delete('/materials/{material}', 'MaterialController@delete')->name('api.materials.delete')->permissions("物料管理:删除物料");

        $api->get('/categories', 'CategoryController@index')->name('api.categories.index')->permissions("物料管理:分类列表");
        // $api->post('/categories', 'CategoryController@store')->name('api.categories.store')->permissions("分类管理:新增分类");
        // $api->delete('/categories/{category}', 'CategoryController@delete')->name('api.categories.delete')->permissions("分类管理:删除分类");


        $api->get('/projects', 'ProjectController@list')->name('api.projects.list')->permissions("项目管理:项目列表");
        $api->post('/projects', 'ProjectController@store')->name('api.projects.store')->permissions("项目管理:新增项目");
        $api->patch('/projects/{project}/status', 'ProjectController@status')->name('api.projects.status')->permissions("项目管理:更新项目状态");
        $api->delete('/projects/{project}', 'ProjectController@delete')->name('api.projects.delete')->permissions("项目管理:删除项目");



        $api->get('/sale_requests', 'SaleRequestController@list')->name('api.sale_requests.list')->permissions("销售需求:销售需求列表");
        $api->get('/sale_request_num', 'SaleRequestController@getUniqueId')->name('api.sale_requests.uuid')->permissions("销售需求:获取销售需求编码");
        $api->post('/sale_requests', 'SaleRequestController@store')->name('api.sale_requests.store')->permissions("销售需求:新增销售需求");
        $api->put('/sale_requests/{request}', 'SaleRequestController@update')->name('api.sale_requests.update')->permissions("销售需求:编辑销售需求");
        $api->delete('/sale_requests/{request}', 'SaleRequestController@delete')->name('api.sale_requests.delete')->permissions("销售需求:删除销售需求");
        $api->post('/sale_requests/{request}/publish', 'SaleRequestController@publish')->name('api.sale_requests.publish')->permissions("销售需求:发布需求");


        $api->get('/preSales', 'PreSaleController@list')->name('api.preSales.list')->permissions("工程售前:工程售前列表");
        $api->put('/preSales/{request}', 'PreSaleController@update')->name('api.preSales.update')->permissions("工程售前:添加工程售前资料");
        $api->post('/preSales/{request}/status', 'PreSaleController@updateStatus')->name('api.preSales.updateStatus')->permissions("工程售前:修改工程售前状态");

        $api->get('/order_num', 'OrderController@getOrderNum')->name('api.order.order_num')->permissions("订单:获取订单编码");
        $api->post('/orders', 'OrderController@store')->name('api.order.store')->permissions("订单:新增订单");
        $api->get('/orders', 'OrderController@list')->name('api.order.list')->permissions("订单:订单列表");

        $api->post('/order_items/{orderItem}/finish', 'OrderItemController@finish')->name('api.orderItem.finish')->permissions("订单:完成订单需求");

        $api->post('/upload', 'UploadController@upload')->name('api.upload.upload')->permissions("附件上传:附件上传");

        $api->get('roles', 'RolesController@index')->name('api.roles.index')->permissions("角色管理:角色列表");
        $api->post('roles', 'RolesController@store')->name('api.roles.store')->permissions("角色管理:新增角色");
        $api->delete('roles/{role}', 'RolesController@delete')->name('api.roles.delete')->permissions("角色管理:删除角色");
        $api->put('roles/{role}', 'RolesController@update')->name('api.roles.update')->permissions("角色管理:分配权限");

        $api->get('permissions', 'RolesController@allPermissions')->name('api.roles.permissions')->permissions("角色管理:权限列表");

        $api->get('users', 'UserController@index')->name('api.users.index')->permissions("用户管理:用户列表");
        $api->post('users', 'UserController@store')->name('api.users.store')->permissions("用户管理:新增用户");
        $api->put('users/{user}', 'UserController@update')->name('api.users.update')->permissions("用户管理:編輯用戶");
        $api->delete('users/{user}', 'UserController@delete')->name('api.users.delete')->permissions("用户管理:删除用户");
    });
});
