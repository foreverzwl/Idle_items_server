<?php

namespace app\api\model;

class Order extends BaseModel
{
    protected $hidden = ['create_time','update_time','delete_time','order_key'];
    //  自定写入时间
    protected $autoWriteTimestamp = true;
    
}