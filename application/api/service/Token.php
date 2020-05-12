<?php

namespace app\api\service;

use app\lib\exception\TokenException;
use Exception;
use think\Cache;
use think\Request;

// Token基类
class Token
{
    // 随机的生成token
    public static function generateToken(){
        // 32个字符组成一组随机字符串
        $randChars = getRandChars(32);
        //第二组为当前时间戳
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        //第三组为salt 盐，为随机数值
        $salt = config('secure.token_salt');
        //用三组字符串，进行md5加密
        return md5($randChars.$timestamp.$salt);
    }

    /**
     * 根据token获取用户id
     */
    public static function getCurrentUid(){
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    /**
     * 获取缓存中的变量
     */
    public static function getCurrentTokenVar($key){
        //  约定token放在header
        $token =   Request::instance()->header('token');
        $vars = Cache::get($token);
        if(!$vars){
            throw new TokenException();
        }else{
            if(!is_array($vars)){
                $vars = json_decode($vars,true);
            }
            if(array_key_exists($key,$vars)){
                return $vars[$key];
            }else{
                throw new Exception('尝试获取的Token变量不存在');
            }
        }


    }

    /**
     * 验证token
     */
    public static function verifyToken($token){
        $exist = Cache::get($token);
        if($exist){
            return true;
        }else{
            return false;
        }

    }
}