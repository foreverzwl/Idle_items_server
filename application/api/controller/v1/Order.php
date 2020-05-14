<?php

namespace app\api\controller\v1;

use app\api\service\Token as Token;
use app\api\service\User as UserService;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use app\api\validate\OrderPlaceValidate;
use app\lib\exception\OrderException;
use app\lib\exception\SuccessMessage;

class Order extends BaseController
{
    private $user;
    private $uid;

    /**
     * 为当前控制器中的方法设置前置方法,''表示为全部方法执行前置操作
     */
    protected $beforeActionList = [
        'verifyUser' => ''
    ];

    /**
     * 从token获取用户并验证用户是否存在
     */
    protected function verifyUser () {
        //  根据Token获取uid
        $uid = Token::getCurrentUid();
        $this->uid = $uid;

        //  根据uid查找用户数据，判断用户是否存在
        $user = UserService::isUserExist($uid);
        $this->user = $user;
    }
    
    /**
     * 检查相关商品的库存
     * @url api/v1/order/create
     */
    public function placeOrder(){
        (new OrderPlaceValidate())->goCheck();

        //  获取参数名为goods的数据
        $goods = input('post.goods/a');

        $orderService = new OrderService();
        $result = $orderService->place($this->uid,$goods);
        return $result;
    }

    /**
     * 获取当前用户全部订单
     * @url api/v1/order/all
     */
    public function getAllOrders(){
        $orders = OrderModel::getAllOrdersByBuyer($this->uid);
        if($orders->isEmpty()){
            throw new OrderException(['msg' => '当前没有订单信息']);
        }
        return $orders;
    }

    /**
     * 取消订单
     * @url api/v1/order/all
     * @id 订单编号
     */
    public function cancelOrder($id){
        $orderService = new OrderService();
        $orderService->cancelOrder($this->uid,$id);
        return json(new SuccessMessage(['msg' => '取消订单成功']),201);
    }
    

}