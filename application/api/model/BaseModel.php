<?php

namespace app\api\model;

use think\Model;
use traits\model\SoftDelete;

class BaseModel extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    //框架自带图片读取器
    public function prefixImgUrl($value){
        return config('setting.img_prefix').$value;
    }
    
}