<?php

namespace app\lib\exception;

class TokenException extends BaseException
{
    public $code = 401;
    public $msg = 'Token过期或无效';
    public $errorCode = 1001;
}