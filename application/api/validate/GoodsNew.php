<?php

namespace app\api\validate;

use app\lib\exception\ParameterException;

class GoodsNew extends BaseValidate
{
    protected $rule = [
        'goods' => 'checkGoodsObj',
        'goods_properties' => 'checkGoodsProperties'
    ];

    //  定义goods、goods_properties各自对应的规则
    protected $goodsRules = [
        'category_id' => 'require|number',
        'description' => 'require|isNotEmpty|max:255',
        'stock' => 'require|isPositiveInteger',
        'price' => 'require|float'
    ];

    protected $propertiesRules = [
        'new' => 'require|isPositiveInteger|<=:10',
        'trade_code' => 'require|number'
    ];

    protected function checkGoodsObj ($value) {
        $validate = new BaseValidate($this->goodsRules);
        $result = $validate->check($value);

        if(!$result){
            throw new ParameterException([
                'msg' => '商品参数错误' 
            ]);
        }
        return true;
    }

    protected function checkGoodsProperties ($value) {
        $validate = new BaseValidate($this->propertiesRules);
        $result = $validate->check($value);
        if(!$result){
            throw new ParameterException([
                'msg' => '商品属性参数错误' 
            ]);
        }
        return true;
    }

}