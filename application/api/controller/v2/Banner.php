<?php 

namespace app\api\controller\v2;

use app\api\validate\IDMustBeSignlessInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\BannerMissException;

class Banner
{
    
    public function getBanner($id)
    {
        return 'This is V2 Version';
    }
}