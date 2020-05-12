<?php

namespace app\lib\exception;

use app\lib\exception\BaseException;

class BannerMissException extends BaseException
{
    //错误码、错误信息、当前url
    public $code = 404;
    public $msg = 'Banner找不到';
    public $errorCode = 1000;
}
