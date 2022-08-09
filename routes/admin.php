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
    $api->post('/login', 'AdminUserController@login');


    /**
     * 需要登录的接口
     */

    $api->group([
        'middleware' => ['jwt.role:admin']
    ], function ($api) {
        $api->get('/users', 'UserController@index')->name('admin.users.index');
        $api->post('/uploads', 'UploadController@upload')->name('admin.upload.upload');
        $api->post('/url_uploads', 'UploadController@uploadBySrc')->name('admin.upload.url');

        $api->get('/info', 'AdminUserController@info')->name('admin.admin_users.info');

        $api->post('/medias', 'MediaController@store')->name('admin.media.store');
        $api->get('/medias', 'MediaController@index')->name('admin.media.index');
        $api->get('/media_list', 'MediaController@list')->name('admin.media.list');
        $api->get('/medias/{resource}', 'MediaController@show')->name('admin.media.show');
        $api->put('/medias/{resource}', 'MediaController@update')->name('admin.media.update');



        $api->post('/channels', 'ChannelController@store')->name('admin.channel.store');
        $api->get('/channels', 'ChannelController@index')->name('admin.channel.index');
        $api->put('/channels/{channel}', 'ChannelController@update')->name('admin.channel.update');
        $api->get('/channels_flatten', 'ChannelController@list')->name('admin.channel.list');
        $api->patch('/channels/{channel}/display', 'ChannelController@display')->name('admin.channel.display');



        $api->post('/play_lists', 'PlayListController@store')->name('admin.play_list.store');
        $api->put('/play_lists/{list}', 'PlayListController@update')->name('admin.play_list.update');
        $api->get('/play_lists', 'PlayListController@index')->name('admin.play_list.index');
        $api->get('/play_lists_flatten', 'PlayListController@list')->name('admin.play_list.list');
        $api->delete('/play_lists/{list}', 'PlayListController@delete')->name('admin.play_list.delete');


        $api->post('/user_account', 'UserAccountController@addDaysToUser')->name('admin.user_account.store');

        $api->post('/system_configs', 'SystemConfigController@add')->name('admin.configs.store');
        $api->get('/system_configs', 'SystemConfigController@index')->name('admin.configs.index');
    });
});
