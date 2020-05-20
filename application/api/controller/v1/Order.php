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
     * @http post
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
     * @http get
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
     * @http get
     */
    public function getWaitingOrders(){
        $orders = OrderModel::getOrderByBuyerAndStatus($this->uid, 0);
        if($orders->isEmpty()){
            throw new OrderException(['msg' => '当前没有订单等待商家同意']);
        }
        return $orders;
    }

    /**
     * 买家用户所有进入交易状态的订单
     * @url api/v1/order/trading
     * @http get
     */
    public function tradingBelongsBuyer(){
        $orders = OrderModel::getOrderByBuyerAndStatus($this->uid, 2);
        if($orders->isEmpty()){
            throw new OrderException(['msg' => '当前没有进入交易状态的订单']);
        }
        return $orders;
    }

    /**
     * 获取买家用户所有成功订单
     * @url api/v1/order/successful_orders
     * @http get
     */
    public function getAllSuccessOrder(){
        $orders = OrderModel::getOrderByBuyerAndStatus($this->uid, 5);
        if($orders->isEmpty()){
            throw new OrderException(['msg' => '当前没有交易成功的订单']);
        }
        return $orders;
    }

    /**
     * 买家用户取消订单
     * @url api/v1/order/cancel_order
     * @http post
     * @id 订单编号
     */
    public function cancelOrder($id){
        $orderService = new OrderService();
        $orderService->cancelOrder($this->uid,$id);
        return json(new SuccessMessage(['msg' => '取消订单成功']),201);
    }

    /**
     * 买家用户确认完成交易
     * @url api/v1/order/confirm_trade
     * @http post
     * @id 订单编号
     */
    public function confirmTrade($id){
        OrderModel::updateOrderStatusByBuyer($id,$this->uid,5);
        return json(new SuccessMessage(['msg' => '交易已完成']),201);
    }

    /**
     * 买家用户取消交易
     * @url api/v1/order/cancel_trade
     * @http post
     * @id 订单编号
     */
    public function cancelTrade($id){
        $orderService = new OrderService();
        $result = $orderService->cancelTrade($this->uid,$id);
        return $result;
        if(!$result['pass']){
            throw new OrderException(['msg' => '操作失败，库存不足']);
        }
        return json(new SuccessMessage(['msg' => '处理订单成功']),201);
    }

    /**
     * 获取商家用户等待处理的订单
     * @url api/v1/order/pending_orders
     * @http get
     */
    public function getPendingOrders(){
        $orders = OrderModel::getOrderByStoreAndStatus($this->uid, 0);
        if($orders->isEmpty()){
            throw new OrderException(['msg' => '当前没有订单需要处理']);
        }
        return $orders;
    }

    /**
     * 商家同意订单
     * @url api/v1/order/agree_order
     * @http post
     * @id 订单编号
     */
    public function agreeOrder($id){
        $orderService = new OrderService();
        $result = $orderService->agreeOrder($this->uid,$id);
        if(!$result['pass']){
            throw new OrderException(['msg' => '操作失败，库存不足']);
        }
        return json(new SuccessMessage(['msg' => '处理订单成功']),201);
    }

    /**
     * 商家拒绝订单
     * @url api/v1/order/refuse_order
     * @http post
     * @id 订单编号
     */
    public function refuseOrder($id){
        OrderModel::updateOrderStatusByStore($id, $this->uid, 3);
        return json(new SuccessMessage(['msg' => '处理订单成功']),201);
    }

    /**
     * 商家所有进入交易状态的订单
     * @url api/v1/order/my_trading
     * @http get
     */
    public function tradingBelongsStore(){
        $orders = OrderModel::getOrderByStoreAndStatus($this->uid, 2);
        if($orders->isEmpty()){
            throw new OrderException(['msg' => '当前没有进入交易状态的订单']);
        }
        return $orders;
    }

    /**
     * 获取商家用户所有订单
     *  @url api/v1/order/my_all_sales
     * @http get
     */
    public function getAllMySales(){
        $orders = OrderModel::getAllOrdersByStore($this->uid);
        if($orders->isEmpty()){
            throw new OrderException(['msg' => '当前没有订单信息']);
        }
        return $orders;
    }
}