<?php

namespace app\lib\exception;

use app\lib\exception\BaseException;

class CategoryException extends BaseException
{
    //错误码、错误信息、当前url
    public $code = 404;
    public $msg = '指定类目不存在';
    public $errorCode = 5000;
}
