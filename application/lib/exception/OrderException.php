<?php

namespace app\lib\exception;

use app\lib\exception\BaseException;

class OrderException extends BaseException
{
    //错误码、错误信息、当前url
    public $code = 404;
    public $msg = '订单不存在，请检查ID';
    public $errorCode = 8000;
}
