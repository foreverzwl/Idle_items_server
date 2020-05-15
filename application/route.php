<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

//  交易方式
Route::get('api/:version/trade/all','api/:version.Trade/getAllTrade');
//  分类目录
Route::get('api/:version/category/all','api/:version.Category/getAllCategories');

// 收藏相关
Route::group('api/:version/collection',function(){
    Route::post('/new_collection','api/:version.Collection/createCollection');
    Route::get('/is_collect/:id','api/:version.Collection/isCollect');
    Route::post('/cancel','api/:version.Collection/cancelCollection');
    Route::get('/my_collections','api/:version.Collection/getAllCollection');
});

// 商品相关路由
Route::group('api/:version/goods',function(){
    Route::get('/all','api/:version.Goods/getAllGoods');
    Route::get('/my/my_goods/on','api/:version.Goods/getMyGoodsOn');
    Route::get('/my/my_goods/off','api/:version.Goods/getMyGoodsOff');
    Route::get('/by_category','api/:version.Goods/getAllInCategory');
    Route::get('/:id','api/:version.Goods/getOne',['id' => '\d+']);
    Route::get('/my/off/:id','api/:version.Goods/deleteOne',['id' => '\d+']);
    Route::post('/my/new_goods','api/:version.Goods/createGoods');
});

//商品轮播图相关路由
Route::group('api/:version/banner',function(){
    Route::post('/my/upload_images/:goodsID','api/:version.Banner/uploadGoodsBanner');
});

//订单相关路由
Route::group('api/:version/order',function(){
    Route::post('/new_order','api/:version.Order/placeOrder');
    Route::get('/all','api/:version.Order/getAllOrders');
    Route::get('/waiting','api/:version.Order/getWaitingOrders');
    Route::get('/pending_orders','api/:version.Order/getPendingOrders');
    Route::get('/trading','api/:version.Order/tradingBelongsBuyer');
    Route::get('/my_trading','api/:version.Order/tradingBelongsStore');
    Route::post('/cancel_order/:id','api/:version.Order/cancelOrder');
    Route::post('/agree_order/:id','api/:version.Order/agreeOrder');
    Route::post('/refuse_order/:id','api/:version.Order/refuseOrder');
});

Route::post('api/:version/chat/:id','api/:version.Chat/sendToMerchants');

//  token相关路由
Route::group('api/:version/token',function(){
    Route::post('/user','api/:version.Token/getToken');
    Route::post('/verify','api/:version.Token/verifyToken');    
});

//  用户地址相关路由
Route::group('api/:version/address',function(){
    Route::get('','api/:version.Address/getAddress');
    Route::post('/operate_address','api/:version.Address/createOrUpdateAddress');
});




// Route::rule('路由表达式','路由地址','请求类型（默认为*，任意请求类型）','路由参数（数组）','变量规则（数组）');

// Route::rule('test','index/index/test');
// Route::get('test','index/index/test'); 
// get的两种传参方式，一：在路由后/参数值  二：在路由后?参数名 = 参数值
// http://xxxx/test/123?name=test，第一个参数id=123，第二个参数name=test
// Route::get('test/:id','index/index/test');
//post除了可以在路由上传递信息，还可以在消息体中传递信息
// Route::post('test/:id','index/index/test');
// 同理：Route::any() Route::post()


