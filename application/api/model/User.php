<?php

namespace app\api\model;



class User extends BaseModel
{
    protected $hidden = ['create_time','update_time','delete_time','open_id'];

    /**
     * 关联地址模型获取用户收获地址
     */
    public function address(){
        return $this->belongsTo('UserAddress','user_id','user_id');
    }

    /**
     * 根据openid获取用户信息
     */
    public static function getByOpenID($openid){
        $user = self::where('open_id','=',$openid)->find();
        return $user;
    }
    
    
}