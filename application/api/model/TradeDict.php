<?php

namespace app\api\model;

use app\api\model\BaseModel;

class TradeDict extends BaseModel
{
    protected $hidden = ['create_time','delete_time', 'on'];
    
    public static function getTrade($tradeID){
        return self::where('trade_method','=',$tradeID)->find();
    }

    //  获取所有启用的交易方式
    public static function getAllOnTrade () {
        return self::where('on',1)->select();
    }
}