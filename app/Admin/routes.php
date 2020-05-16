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
    $router->any('users/files', 'FileController@handle');

    // 基础管理
    $router->resource('/auth/users', 'UserController');
    $router->get('api/stores', 'UserController@stores');

    // 公告管理
    $router->resource('shop/announcement', 'Shop\AnnouncementController');

    // 广告（banner）管理
    $router->resource('shop/ad', 'Shop\AdController');

    // 商品分类管理
    $router->resource('shop/goodsCategory', 'Shop\GoodsCategoryController');

    // 商品管理
    $router->resource('shop/store', 'Shop\StoreController');
    $router->resource('shop/goods', 'Shop\GoodsController');
    $router->get('api/getGoodsAttr', 'Shop\GoodsController@getGoodsAttr');
    $router->post('api/createGoodsAttr', 'Shop\GoodsController@createGoodsAttr');
    $router->get('api/cat', 'Shop\GoodsController@cats');

    // 订单管理
    $router->resource('shop/order', 'Shop\OrderController');
    $router->post('api/order/delivery', 'Shop\OrderController@delivery')->name('订单发货');

    // 优惠券管理
    $router->resource('shop/coupon', 'Shop\CouponController');
    $router->get('api/goods', 'Shop\CouponController@goods');
    $router->get('api/goodsByIds', 'Shop\CouponController@goodsByIds');
    $router->get('api/goodsCat', 'Shop\CouponController@goodsCat');
    $router->get('api/goodsCatByIds', 'Shop\CouponController@goodsCatByIds');

    // 转盘活动
    $router->resource('shop/luckyDraw', 'Shop\LuckyDrawController');

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
