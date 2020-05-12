<?php

namespace app\api\controller\v1;

use app\api\service\Token as Token;
use app\api\service\User as UserService;
use app\api\service\Order as OrderService;
use app\api\validate\OrderPlaceValidate;

class Order
{
    /**
     * 检查相关商品的库存
     * @url api/v1/order/create
     */
    public function placeOrder(){
        (new OrderPlaceValidate())->goCheck();

        //  获取参数名为goods的数据
        $goods = input('post.goods/a');
        $uid = Token::getCurrentUid();
        UserService::isUserExist($uid);
        $orderService = new OrderService();
        $result = $orderService->place($uid,$goods);
        return $result;
    }

    

}