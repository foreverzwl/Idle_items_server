<?php

namespace app\api\service;

use app\api\model\User as UserModel;
use app\lib\exception\UserException;

class User
{
    /**
     * 判断用户是否存在
     * @return true
     */
    public static function isUserExist($uid){
        //  根据uid查找用户数据，判断用户是否存在
        $user = UserModel::get($uid);
        if(!$user){
            throw new UserException();
        }
        return $user;
    }
}