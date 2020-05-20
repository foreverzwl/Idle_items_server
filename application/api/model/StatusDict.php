<?php

namespace app\api\model;

use app\api\model\BaseModel;

class StatusDict extends BaseModel
{
    protected $hidden = ['create_time','delete_time', 'menu', 'on'];
    //  关闭update_time字段自动写入
    protected $updateTime = false;
    
    //  获取所有启用的状态
    public static function getAllOnStatus () {
        return self::where('on',1)->select();
    }
}