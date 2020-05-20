<?php 

namespace app\api\controller\v1;

use app\api\model\Goods as GoodsModel;
use app\api\model\UserAddress as UserAddressModel;
use app\api\service\User as UserService;
use app\api\service\Goods as GoodsService;
use app\api\service\Token as Token;
use app\api\validate\GoodsNew;
use app\api\validate\IDMustBeSignlessInt;
use app\lib\exception\GoodsException;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserAddressException;
use think\Request;

class Goods
{

    /**
     * 获取所有商品
     * @url /api/v1/goods/all
     */
    public function getAllGoods(){
        $goods = GoodsModel::getAll();
        $goods->hidden(['stock','owner']);
        if($goods->isEmpty()){
            throw new GoodsException();
        }
        return $goods;
    }

    /**
     * 获取某一分类所有的商品
     * @url /api/v1/goods/by_category
     * @http GET
     * @id 分类id
     */
    public function getAllInCategory($id)
    {
        (new IDMustBeSignlessInt())->goCheck();
        $goods = GoodsModel::getGoodsByCategoryId($id);
        if($goods->isEmpty()){
            throw new GoodsException();
        }
        return $goods;
    }

    /**
     * 获取商品详情
     * @url /api/v1/goods/
     * @http GET
     * @id 商品id
     */
    public function getOne($id){
        (new IDMustBeSignlessInt())->goCheck();
        $goods = GoodsModel::getOneDetail($id);
        if(!$goods){
            throw new GoodsException();
        }
        return $goods;
    }

    /**
     * 删除商品
     * @url /api/v1/goods/my/off/
     * @HTTP GET
     * @id 商品id
     */
    public function deleteOne($id){
        (new IDMustBeSignlessInt())->goCheck();   
        //  根据Token获取uid
        $uid = Token::getCurrentUid();
        //  判断当前用户是否存在     
        UserService::isUserExist($uid);
        //  判断当前用户是否是商品所有者，否则抛出异常
        GoodsService::isGoodsOwner($id,$uid);
        GoodsModel::destoryByOwner($id);
        return json(new SuccessMessage(['msg'=>'删除商品成功']),201);
    }

    /**
     * 获取用户发布的商品（未下架的）
     * @url /api/v1/goods/my/my_goods/on
     * @HTTP GET
     */
    public function getMyGoodsOn(){    
        //  根据Token获取uid
        $uid = Token::getCurrentUid();
        UserService::isUserExist($uid);
        //  获取用户发布的商品
        $goods = GoodsModel::getOnGoodsByOwner($uid);
        if($goods->isEmpty()){
            throw new GoodsException(['msg' => '当前用户没有未下架商品']);
        }
        return $goods;
    }

    /**
     * 获取用户发布的商品（已下架的）
     * @url /api/v1/goods/my/my_goods/off
     * @HTTP GET
     */
    public function getMyGoodsOff(){    
        //  根据Token获取uid
        $uid = Token::getCurrentUid();
        UserService::isUserExist($uid);
        //  获取用户发布的商品
        $goods = GoodsModel::getOffGoodsByOwner($uid);
        if($goods->isEmpty()){
            throw new GoodsException(['msg' => '当前用户没有下架商品']);
        }
        return $goods;
    }

    /**
     * 用户发布商品
     * @url /api/v1/goods/my/new_goods
     * @HTTP POST
     */
    public function createGoods(){
        (new GoodsNew())->goCheck();
        $goodsData = input('post.');
        $uid = Token::getCurrentUid();  
        UserService::isUserExist($uid);
        //  用户发布的商品
        $goodsService = new GoodsService();
        $goods_id = $goodsService->createMyGoods($uid,$goodsData);
        return $goods_id;
    }

    /**
     * 根据商品获取商家的个人信息
     * @url /api/v1/goods/owner/id
     * @HTTP get
     * @id 商品id
     */
    public function getOwnerInfoByGoods($id){
        $uid = Token::getCurrentUid();  
        UserService::isUserExist($uid);
        $owner = GoodsModel::getOwnerInfoByGoods($id);
        if($owner->isEmpty()){
            throw new UserAddressException();
        }
        $buyer = UserAddressModel::getAddressByID($uid);
        $result = ['buyerId' => $buyer['uid'],'storeId' => $owner[0]['user']['address']['uid'],'buyerName' => $buyer['name'],'storeName' => $owner[0]['user']['address']['name']];
        return $result;
    }
}


