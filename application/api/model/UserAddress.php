<?php

namespace app\api\model;

class UserAddress extends BaseModel{

    protected $hidden = ['address_id', 'user_id', 'update_time', 'delete_time'];

    /**
     * 根据openid获取用户基本信息以及地址信息
     */
    public static function getAddressByID($uid){
        $address = self::where('user_id','=',$uid)->find();
        return $address;
    }
}