<?php

namespace app\api\validate;

class AddressNew extends BaseValidate
{
    protected $rule = [
        'name' => 'require|isNotEmpty',
        'mobile' => 'require|isMobile',
        'country' => 'require|isNotEmpty',
        'province' => 'require|isNotEmpty',
        'city' => 'require|isNotEmpty',
        'area' => 'require|isNotEmpty',
        'detail' => 'require|isNotEmpty'
    ];
}
