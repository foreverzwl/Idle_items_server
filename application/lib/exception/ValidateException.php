<?php

namespace app\lib\exception;

use app\lib\exception\BaseException;

class ValidateException extends BaseException
{
    //错误码、错误信息、当前url
    public $code = 400;
    public $msg = '参数错误';
    public $errorCode = 1000;
}
