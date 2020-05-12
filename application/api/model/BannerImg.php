<?php

namespace app\api\model;

use app\api\model\BaseModel;

class BannerImg extends BaseModel
{
    protected $hidden = ['create_time','update_time','delete_time','banner_id','img_id'];
    
    /**
     * 读取器，用于拼接图片地址
     * get+字段名称+Attr
     */
    public function getUrlAttr($value)
    {
        return $this->prefixImgUrl($value);
    }
}