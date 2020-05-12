<?php

namespace app\lib\exception;

use app\lib\exception\BaseException;

class ImageUploadException extends BaseException
{
    //错误码、错误信息、当前url
    public $code = 413;
    public $msg = '上传文件超出限制';
    public $errorCode = 5000;
}
