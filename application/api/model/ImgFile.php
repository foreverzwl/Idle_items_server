<?php

namespace app\api\model;

use app\api\model\BaseModel;

class ImgFile extends BaseModel
{    
    protected $hidden = ['create_time','update_time','img_id'];
    
}