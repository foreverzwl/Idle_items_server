<?php 

namespace app\api\controller\v1;

use app\api\model\TradeDict as TradeModel;
use app\lib\exception\TradeException;

class Trade
{
    /**
     * 获取所有分类列表
     * @url:/api/v1/trade/all
     * @http GET
     */
    public function getAllTrade()
    {
        $trade = TradeModel::getAllOnTrade();
        if($trade->isEmpty()){
            throw new TradeException();
        }
        return $trade;
    }
}