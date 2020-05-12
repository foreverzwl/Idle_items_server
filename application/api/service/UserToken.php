<?php

namespace app\api\service;

use app\api\model\User as UserModel;
use app\lib\enum\ScopeEnum;
use app\lib\exception\WXException;
use app\lib\exception\TokenException;
use Exception;

class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    function __construct($code)
    {
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'),
            $this->wxAppID,$this->wxAppSecret,$this->code);
    }

    //使用code换取微信用户标识openid
    public function get(){
        $result = http_get($this->wxLoginUrl);
        //字符串转数组
        $wxResult = json_decode($result,true);
        //如果code码传入非法，微信服务器将返回空
        if(empty($wxResult)){
            throw new Exception('获取session_key、openID异常，微信内部错误');
        }else{
            //code码传入合法，得到应答报文，进一步判断
            $loginFail = array_key_exists('errcode',$wxResult);
            //存在errcode，换取失败
            if($loginFail){
                //抛出微信提示异常
                $this->wxLoginError($wxResult);
            }else{
                // 进行授权
                return $this->grantToken($wxResult);
            }
        }
    }

    private function wxLoginError($wxResult){
        throw new WXException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']
        ]);
    }

    //生成令牌
    private function grantToken($wxResult){
        //取出openid
        $openid = $wxResult['openid'];
        //查询openid是否存在，不存在则添加用户
        $user = UserModel::getByOpenID($openid);
        if($user){
            $uid = $user->user_id;
        }else{
            $uid = $this->createUser($openid);
        }
        //生成令牌，准备缓存数据，写入缓存。令牌做键名，对应值为wxResult、uid、scope权限
        $cacheValue = $this->prepareCacheValue($wxResult,$uid);
        $token = $this->saveToCache($cacheValue);
        return $token;
    }

    //写入缓存
    private function saveToCache($cacheValue){
        //取令牌做键名
        $key = self::generateToken();
        //将数组转换为字符串
        $value = json_encode($cacheValue);
        //读取缓存失效时间，以缓存失效时间作为令牌失效时间
        $expire_in = config('setting.token_expire_in');
        
        $request = cache($key,$value,$expire_in);
        if(!$request){
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 1005
            ]);
        }
        return $key;
    }

    //准备缓存的值
    private function prepareCacheValue($wxResult,$uid){
        $value = $wxResult;
        $value['uid'] = $uid;
        $value['scope'] = ScopeEnum::User;
        return $value;
    }

    //新增用户
    private function createUser($openid){
        $user = UserModel::create([
            'user_id' => $this->generateUserID($openid),
            'open_id' => $openid,
            'create_time' => getFormateTime('Y-m-d H:i:s'),
            'update_time' => getFormateTime('Y-m-d H:i:s')
        ]);
        return $user->user_id;
    }

    // 随机生成用户id
    private function generateUserID($openid){
        return getFormateTime('YmdHis').substr($openid,0,8);
    }
}