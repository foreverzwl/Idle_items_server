<?php

namespace app\api\model;

use think\Model;
use traits\model\SoftDelete;

class BaseModel extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    //  自定写入时间
    protected $autoWriteTimestamp = 'dateTime';
    protected $createTime = 'create_time';
    //  自动写入更新时间
    protected $updateTime = 'update_time';
    
    //框架自带图片读取器
    public function prefixImgUrl($value){
        return config('setting.img_prefix').$value;
    }
    
}