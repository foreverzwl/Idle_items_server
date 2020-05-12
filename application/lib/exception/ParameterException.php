<?php

namespace app\lib\exception;

use app\lib\exception\BaseException;

class ParameterException extends BaseException
{
    //错误码、错误信息、当前url
    public $code = 406;
    public $msg = '参数异常';
    public $errorCode = 5000;
}
