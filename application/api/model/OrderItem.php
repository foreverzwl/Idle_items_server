<?php

namespace app\api\model;

class OrderItem extends BaseModel
{
    protected $hidden = ['create_time','update_time','delete_time','order_key'];

    /**
     * 软删除数据
     */
    public static function softDelete($orderNo){
        self::destroy(['order_no' => $orderNo]);
    }
}