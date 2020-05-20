<?php

namespace app\lib\exception;

class UserAddressException extends BaseException
{
    public $code = 404;
    public $msg = '用户现住址不存在';
    public $errorCode = 6000;
}