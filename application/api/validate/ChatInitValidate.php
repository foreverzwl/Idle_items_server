<?php

namespace app\api\validate;

class ChatInitValidate extends BaseValidate
{
    protected $rule = [
        'client_id' => 'require|isNotEmpty'
    ];
}
