<?php

namespace app\lib\exception;

use think\Exception;
 
class BaseException extends Exception
{
    //错误码、错误信息、当前url
    public $code = 400;
    public $msg = '参数错误';
    public $errorCode = 1000;

    //传入可选参数
    public function __construct($params = [])
    {
        //如果传入参数不是数组，返回默认值
        if(!is_array($params)){
            return ;
        }
        if(array_key_exists('code',$params)){
            $this->code = $params['code'];
        }
        if(array_key_exists('msg',$params)){
            $this->msg = $params['msg'];
        }
        if(array_key_exists('errorCode',$params)){
            $this->errorCode = $params['errorCode'];
        }
    }

    
}
