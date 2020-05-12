<?php

namespace app\api\model;

class OrderItem extends BaseModel
{
    protected $hidden = ['create_time','update_time','delete_time','order_key'];
    
}