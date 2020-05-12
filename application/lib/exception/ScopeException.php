<?php

namespace app\lib\exception;

class ScopeException extends BaseException
{
    public $code = 403;
    public $msg = '无权限操作';
    public $errorCode = 4000;
}
