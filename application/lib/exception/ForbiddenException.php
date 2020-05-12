<?php

namespace app\lib\exception;

use app\lib\exception\BaseException;

class ForbiddenException extends BaseException
{
    //错误码、错误信息、当前url
    public $code = 403;
    public $msg = '无权限操作';
    public $errorCode = 1001;
}
