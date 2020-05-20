<?php

namespace app\lib\exception;

class ChatMessage
{
    public $code = 201;
    public $msg = '操作成功';
    public $errorCode = 0;
    public $data = '';

    //传入可选参数
    public function __construct($params = [])
    {
        //如果传入参数不是数组，返回默认值
        if(!is_array($params)){
            return ;
        }
        if(array_key_exists('data',$params)){
            $this->data = $params['data'];
        }
    }

}
