<?php

namespace app\lib\exception;

use app\lib\exception\BaseException;

class WXException extends BaseException
{
    //错误码、错误信息、当前url
    public $code = 400;
    public $msg = '微信接口调用失败';
    public $errorCode = 999;
}
