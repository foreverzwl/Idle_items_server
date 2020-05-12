<?php

namespace app\api\validate;

use app\lib\exception\ParameterException;

class OrderPlaceValidate extends BaseValidate
{
    protected $rule = [
        'goods' => 'require|checkGoodsArr'
    ];
    protected $singleRule = [
        'goods_id' => 'require|isPositiveInteger',
        'count' => 'require|isPositiveInteger'
    ];

    protected function checkGoodsArr($values){
        if(!is_array($values)){
            throw new ParameterException([
                'msg' => '商品参数不正确'
            ]);
        }

        if(empty($values)){
            throw new ParameterException([
                'msg' => '商品列表不能为空'
            ]);
        }
        foreach ($values as $value){
            $this->checkGoods($value);
        }
        return true;
    }

    protected function checkGoods($value){
        $validate = new BaseValidate($this->singleRule);
        $result = $validate->check($value);
        if(!$result){
            throw new ParameterException([
                'msg' => '商品列表参数错误' 
            ]);
        }
        return true;
    }
}