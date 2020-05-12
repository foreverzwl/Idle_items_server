<?php 

namespace app\api\controller\v1;

use app\api\service\UserToken;
use app\api\validate\TokenGet;
use app\lib\exception\ParameterException;

use app\api\service\Token as TokenService;

class Token
{
    /**
     * 获取令牌
     * @url api/v1/token/user
     * @http POST
     * @code 微信登录授权code码
     */
    public function getToken($code = '')
    {
        (new TokenGet())->goCheck();
        $user_token = new UserToken($code);
        $token = $user_token->get();
        return [
            'token' => $token
        ];
    }

    /**
     * 验证令牌
     * @url  api/v1/token/verify
     * @http post
     * @token token值
     */
    public function verifyToken($token=''){
        if(!$token){
            throw new ParameterException([
                'token不允许为空'
            ]);
        }
        $valid = TokenService::verifyToken($token);
        return [
            'isValid' => $valid
        ];
    }
}


