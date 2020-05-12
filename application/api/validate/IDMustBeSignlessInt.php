<?php

 namespace app\api\validate;

 use app\api\validate\BaseValidate;

 class IDMustBeSignlessInt extends BaseValidate
 {
    protected $rule = [
        'id' => 'require|isPositiveInteger',
    ];
 }