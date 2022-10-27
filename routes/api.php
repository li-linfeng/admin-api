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
        'middleware' => ['auth.jwt', 'permission']

    ], function ($api) {
        $api->get('/user/info', 'UserController@info')->name('api.user.info')->permissions('用户信息:用户信息');
        $api->get('/user_permissions', 'UserController@getUserPermissions')->name('api.user.user_permission')->permissions('用户信息:用户权限');

        $api->get('/materials', 'MaterialController@index')->name('api.materials.index')->permissions("物料管理:物料列表");

        $api->get('/material_tree', 'MaterialController@tree')->name('api.materials.tree')->permissions("物料管理:获取物料树");

        $api->post('/materials', 'MaterialController@store')->name('api.materials.update')->permissions("物料管理:增加物料");
        $api->post('/materials/{material}/rel', 'MaterialController@bindRel')->name('api.materials.rel')->permissions("物料管理:添加子组件");

        $api->get('/categories', 'CategoryController@index')->name('api.categories.index')->permissions("物料管理:分类列表");



        $api->get('/projects', 'ProjectController@list')->name('api.projects.list')->permissions("项目管理:项目列表");
        $api->post('/projects', 'ProjectController@store')->name('api.projects.store')->permissions("项目管理:新增项目");
        $api->patch('/projects/{project}/status', 'ProjectController@status')->name('api.projects.status')->permissions("项目管理:更新项目状态");
        $api->delete('/projects/{project}', 'ProjectController@delete')->name('api.projects.delete')->permissions("项目管理:删除项目");
        $api->get('/projects/download', 'ProjectController@download')->name('api.projects.download')->permissions("项目管理:导出项目");
       


        $api->get('/sale_requests', 'SaleRequestController@list')->name('api.sale_requests.list')->permissions("销售需求:销售需求列表");
        $api->post('/sale_requests', 'SaleRequestController@store')->name('api.sale_requests.store')->permissions("销售需求:新增销售需求");
        $api->put('/sale_requests/{request}', 'SaleRequestController@update')->name('api.sale_requests.update')->permissions("销售需求:编辑销售需求");
        $api->delete('/sale_requests/{request}', 'SaleRequestController@delete')->name('api.sale_requests.delete')->permissions("销售需求:删除销售需求");
        $api->post('/sale_requests/{request}/publish', 'SaleRequestController@publish')->name('api.sale_requests.publish')->permissions("销售需求:发布需求");
        $api->get('/sale_requests/download', 'SaleRequestController@download')->name('api.sale_requests.download')->permissions("销售需求:导出销售需求");


        $api->get('/preSales', 'PreSaleController@list')->name('api.preSales.list')->permissions("工程售前:工程售前列表");
        $api->put('/preSales/{request}', 'PreSaleController@update')->name('api.preSales.update')->permissions("工程售前:添加工程售前资料");
        $api->post('/preSales/{request}/status', 'PreSaleController@updateStatus')->name('api.preSales.updateStatus')->permissions("工程售前:修改工程售前状态");
        $api->get('/preSales/download', 'PreSaleController@download')->name('api.preSales.download')->permissions("工程售前:导出工程售前");

        $api->get('/order_num', 'OrderController@getOrderNum')->name('api.order.order_num')->permissions("订单:获取订单编码");
        $api->post('/orders', 'OrderController@store')->name('api.order.store')->permissions("订单:新增订单");
        $api->get('/orders', 'OrderController@list')->name('api.order.list')->permissions("订单:订单列表");
        $api->get('/orders/download', 'OrderController@download')->name('api.order.download')->permissions("订单:导出订单");

        $api->post('/order_items/{orderItem}/finish', 'OrderItemController@finish')->name('api.order.finish')->permissions("订单:完成订单需求");
        $api->post('/order_items/{orderItem}/bind', 'OrderItemController@bindMaterial')->name('api.order.bind')->permissions("订单:绑定物料号");
        $api->get('/order_items/{orderItem}/download', 'OrderItemController@download')->name('api.order.download')->permissions("订单:下载Boom图纸");


        $api->post('/upload', 'UploadController@upload')->name('api.upload.upload')->permissions("附件上传:附件上传");

        $api->get('roles', 'RolesController@index')->name('api.roles.index')->permissions("角色管理:角色列表");
        $api->post('roles', 'RolesController@store')->name('api.roles.store')->permissions("角色管理:新增角色");
        $api->delete('roles/{role}', 'RolesController@delete')->name('api.roles.delete')->permissions("角色管理:删除角色");
        $api->put('roles/{role}', 'RolesController@update')->name('api.roles.update')->permissions("角色管理:分配权限");

        $api->get('permissions', 'RolesController@allPermissions')->name('api.roles.permissions')->permissions("角色管理:权限列表");

        $api->get('users', 'UserController@index')->name('api.users.index')->permissions("用户管理:用户列表");
        $api->get('user_list', 'UserController@list')->name('api.users.list')->permissions("用户管理:查询用户");
        $api->post('users', 'UserController@store')->name('api.users.store')->permissions("用户管理:新增用户");
        $api->put('users/{user}', 'UserController@update')->name('api.users.update')->permissions("用户管理:編輯用戶");
        $api->delete('users/{user}', 'UserController@delete')->name('api.users.delete')->permissions("用户管理:删除用户");


        $api->get('handlers', 'HandlerController@index')->name('api.handlers.index')->permissions("处理人管理:处理人列表");
        $api->put('handlers/{handler}/user', 'HandlerController@setHandler')->name('api.handlers.set_user')->permissions("处理人管理:设置处理人");

        $api->get('news', 'NewsController@index')->name('api.dashboard.news_index')->permissions("首页:每日要闻列表");
        $api->put('news/{news}', 'NewsController@update')->name('api.dashboard.news_update')->permissions("首页:每日要闻编辑");
        $api->post('news', 'NewsController@store')->name('api.dashboard.news_store')->permissions("首页:每日要闻新增");
        $api->delete('news/{news}', 'NewsController@delete')->name('api.dashboard.news_delete')->permissions("首页:每日要闻删除");
        $api->get('news/{news}', 'NewsController@show')->name('api.dashboard.news_show')->permissions("首页:每日要闻详情");

        $api->get('weather', 'WeatherController@weather')->name('api.dashboard.weather')->permissions("首页:首页天气");
        $api->get('todo', 'WeatherController@todo')->name('api.dashboard.todo')->permissions("首页:每日代办");
        $api->put('todo/{todo}/read', 'WeatherController@read')->name('api.dashboard.read')->permissions("首页:每日代办已读");
        $api->post('todo/readAll', 'WeatherController@readAll')->name('api.dashboard.read_all')->permissions("首页:每日代办全部已读");
    });
});
