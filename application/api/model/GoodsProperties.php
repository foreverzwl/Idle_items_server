<?php

namespace app\api\model;

use app\api\model\BaseModel;

class GoodsProperties extends BaseModel
{
    protected $hidden = ['create_time','update_time','delete_time','proper_id','goods_id','trade_code'];


    /**
     * 根据trade_method取字典对应交易方式
     */
    public function trade(){
        return $this->belongsTo('TradeDict','trade_code','code');
    }
}