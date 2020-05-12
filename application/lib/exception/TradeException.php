<?php

namespace app\lib\exception;

use app\lib\exception\BaseException;

class TradeException extends BaseException
{
    //错误码、错误信息、当前url
    public $code = 404;
    public $msg = '指定交易方式不存在';
    public $errorCode = 5000;
}
