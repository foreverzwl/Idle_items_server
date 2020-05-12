<?php

namespace app\api\controller\v1;

use app\api\model\BannerImg;
use app\api\validate\IDMustBeSignlessInt;
use app\api\service\Token as Token;
use app\api\service\User as UserService;
use app\api\service\Banner as BannerService;
use app\lib\exception\SuccessMessage;

class Banner
{
    /**
     * @url api/v1/banner/my/upload_images/:goodsID
     */
    public function uploadGoodsBanner ($goodsID) {
        //  根据Token获取uid
        $uid = Token::getCurrentUid();
        //  判断当前用户是否存在     
        UserService::isUserExist($uid);
        (new BannerService())->uploadBannerImg($uid,$goodsID);
        return 0;
    }
}