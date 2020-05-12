<?php

namespace app\lib\exception;

use app\lib\exception\BaseException;

class CollectionException extends BaseException
{
    //错误码、错误信息、当前url
    public $code = 404;
    public $msg = '当前没有收藏的商品';
    public $errorCode = 5000;
}
