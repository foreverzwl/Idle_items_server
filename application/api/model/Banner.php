<?php

namespace app\api\model;

use app\api\model\BaseModel;

class Banner extends BaseModel
{
    protected $hidden = ['create_time','update_time','delete_time','banner_id','goods_id'];


    /**
     * 获取与banner相关联的banner_img
     */
    public function bannerImgs(){
        //关联模型模型名，外键，当前模型主键
        return $this->hasMany('banner_img','banner_id','banner_id')->order('order','asc');
    }

    

}