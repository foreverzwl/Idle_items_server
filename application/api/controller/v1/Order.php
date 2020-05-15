<?php

namespace app\api\controller\v1;

use app\api\service\Token as Token;
use app\api\service\User as UserService;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use app\api\validate\OrderPlaceValidate;
use app\lib\exception\OrderException;
use app\lib\exception\SuccessMessage;
use Exception;

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
     * 检查相关商品的库存，创建订单
     * @url api/v1/order/new_order
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
     * 获取买家用户全部订单
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
     * 获取买家用户等待同意订单
     * @url api/v1/order/waiting
     */
    public function getWaitingOrders(){
        $orders = OrderModel::getWaitingOrdersByBuyer($this->uid);
        if($orders->isEmpty()){
            throw new OrderException(['msg' => '当前没有订单等待商家同意']);
        }
        return $orders;
    }

    /**
     * 买家用户取消订单
     * @url api/v1/order/cancel_order
     * @id 订单编号
     */
    public function cancelOrder($id){
        $orderService = new OrderService();
        $orderService->cancelOrder($this->uid,$id);
        return json(new SuccessMessage(['msg' => '取消订单成功']),201);
    }

    /**
     * 买家用户所有进入交易状态的订单
     * @url api/v1/order/trading
     * @id 订单编号
     */
    public function tradingBelongsBuyer(){
        $orders = OrderModel::getOrderByBuyerAndStatus($this->uid, 2);
        if($orders->isEmpty()){
            throw new OrderException(['msg' => '当前没有进入交易状态的订单']);
        }
        return $orders;
    }

    /**
     * 获取商家用户等待处理的订单
     * @url api/v1/order/pending_orders
     */
    public function getPendingOrders(){
        $orders = OrderModel::getWaitingOrdersByStore($this->uid);
        if($orders->isEmpty()){
            throw new OrderException(['msg' => '当前没有订单需要处理']);
        }
        return $orders;
    }

    /**
     * 商家同意订单
     * @url api/v1/order/agree_order
     * @id 订单编号
     */
    public function agreeOrder($id){
        $goods = OrderModel::getOrderDetailByStore($id, $this->uid);
        if (!$goods) {
            throw new OrderException(['msg' => '订单不存在']);
        }
        $orderService = new OrderService();
        $result = $orderService->agreeOrder($this->uid,$goods->item,$id);
        if(!$result['pass']){
            throw new OrderException(['msg' => '操作失败，库存不足']);
        }
        return json(new SuccessMessage(['msg' => '处理订单成功']),201);
    }

    /**
     * 商家拒绝订单
     * @url api/v1/order/refuse_order
     * @id 订单编号
     */
    public function refuseOrder($id){
        OrderModel::updateOrderStatusByStore($id, $this->uid, 3);
        return json(new SuccessMessage(['msg' => '处理订单成功']),201);
    }

    /**
     * 商家所有进入交易状态的订单
     * @url api/v1/order/my_trading
     * @id 订单编号
     */
    public function tradingBelongsStore(){
        $orders = OrderModel::getOrderByStoreAndStatus($this->uid, 2);
        if($orders->isEmpty()){
            throw new OrderException(['msg' => '当前没有进入交易状态的订单']);
        }
        return $orders;
    }
}