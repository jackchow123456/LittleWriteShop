<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    // 基础管理
    $router->resource('/auth/users', 'UserController');
    $router->get('/api/stores', 'UserController@stores');

    // 商品管理
    $router->resource('shop/store', 'Shop\StoreController');
    $router->resource('shop/goods', 'Shop\GoodsController');
    $router->get('/api/getGoodsAttr', 'Shop\GoodsController@getGoodsAttr');
    $router->post('/api/createGoodsAttr', 'Shop\GoodsController@createGoodsAttr');

    // 商品规格
    $router->resource('shop/goodsAttr', 'Shop\GoodsAttrController');
    // 文件管理
    $router->get('fileManager', 'FileManagerController@index')->name('fileManager.index')->middleware('permission:fileManager');
    $router->get('fileManager/image', 'FileManagerController@image')->name('fileManager.image');
    $router->get('fileManager/folders', 'FileManagerController@folders')->name('fileManager.folders');
    $router->get('fileManager/multiUpload', 'FileManagerController@multiUpload')->name('fileManager.multiUpload');
    $router->post('fileManager/listFolders', 'FileManagerController@listFolders')->name('fileManager.listFolders');
    $router->post('fileManager/files', 'FileManagerController@files')->name('fileManager.files');
    $router->post('fileManager/create', 'FileManagerController@create')->name('fileManager.create');
    $router->post('fileManager/directory', 'FileManagerController@directory')->name('fileManager.directory');
    $router->post('fileManager/delete', 'FileManagerController@delete')->name('fileManager.delete');
    $router->post('fileManager/move', 'FileManagerController@move')->name('fileManager.move');
    $router->post('fileManager/copy', 'FileManagerController@copy')->name('fileManager.copy');
    $router->post('fileManager/rename', 'FileManagerController@rename')->name('fileManager.rename');
    $router->post('fileManager/upload', 'FileManagerController@upload')->name('fileManager.upload');
});
