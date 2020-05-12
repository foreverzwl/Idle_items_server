<?php

namespace app\api\validate;

class CollectionNew extends BaseValidate
{
    protected $rule = [
        'goods_id' => 'require|isPositiveInteger'
    ];
}
