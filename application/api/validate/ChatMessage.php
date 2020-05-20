<?php

namespace app\api\validate;

class ChatMessage extends BaseValidate
{
    protected $rule = [
        'client_id' => 'require|isNotEmpty',
        'message' => 'require|isNotEmpty',
        'receiver_uid' => 'require|isNotEmpty'
    ];
}
