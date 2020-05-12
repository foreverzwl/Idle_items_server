<?php

namespace app\lib\exception;

use app\lib\exception\BaseException;

class GoodsException extends BaseException
{
    //错误码、错误信息、当前url
    public $code = 404;
    public $msg = '商品不存在';
    public $errorCode = 5000;
}
