<?php

namespace app\api\model;

class Order extends BaseModel
{
    protected $hidden = ['create_time','update_time','delete_time','order_key'];

    /**
     * 获取订单详细项目（包括软删除数据）
     */
    public function item () {
        return $this->hasMany('OrderItem','order_no','order_no')->removeOption('soft_delete');;
    }

    /**
     * 获取订单交易方式
     */
    public function trade () {
        return $this->belongsTo('TradeDict','trade_code','code');
    }

    /**
     * 获取订单状态
     */
    public function status () {
        return $this->belongsTo('StatusDict','status','code');
    }
    
    /**
     * 根据买家id获取全部订单，包括软删除数据
     */
    public static function getAllOrdersByBuyer ($uid) {
        $orders = self::with(['item', 'trade',
                            'status' => function($query){
                                $query->where(['on' => 1, 'menu' => 'order']);
                            }
                        ])
                        ->where('buyer_id',$uid)
                        ->order('update_time desc')
                        ->select()
                        ->hidden(['trade_code', 'buyer_id', 'buyer_name', 'buyer_mobile', 'buyer_address']);
        return $orders;
    }

    /**
     * 根据订单编号获取订单
     */
    public static function getOrderByOrderNo ($orderNo) {
        $order = self::where('order_no','=',$orderNo)->find();
        return $order;
    }

    /**
     * 更新订单状态
     */
    public static function updateOrderStatus ($orderNo, $uid, $status) {
        self::where(['order_no' => $orderNo,'buyer_id' => $uid])->update(['status' => $status]);
    }
}