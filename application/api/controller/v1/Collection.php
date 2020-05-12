<?php 

namespace app\api\controller\v1;

use app\api\service\Token as Token;
use app\api\service\User as UserService;
use app\api\model\CollectionGoods as CollectionGoodsModel;
use app\api\validate\CollectionNew;
use app\api\validate\IDMustBeSignlessInt;
use app\lib\exception\CollectionException;
use app\lib\exception\SuccessMessage;

class Collection
{
    /**
     * 新增收藏关系
     * @url:/api/v1/collection/new_collection
     * @http post
     */
    public function createCollection()
    {
        $validate = new CollectionNew();
        $validate->goCheck();
        //  根据Token获取uid
        $uid = Token::getCurrentUid();
        UserService::isUserExist($uid);
        $data = $validate->getDataByRule(input('post.'));
        $data['user_id'] = $uid;
        $collection = new CollectionGoodsModel();
        $collection->insert($data);
        return json(new SuccessMessage(),201);
    }

    /**
     * 查询是否收藏该商品
     * @url:/api/v1/collection/is_collect
     * @http get
     * @id 商品id
     */
    public function isCollect($id)
    {
        (new IDMustBeSignlessInt())->goCheck();   
        //  根据Token获取uid
        $uid = Token::getCurrentUid();
        UserService::isUserExist($uid);
        $collection = CollectionGoodsModel::getCollection($id,$uid);
        if($collection->isEmpty()){
            $data = ['code'=> 0];
        }else{
            $data = ['code'=> 1];
        }
        return json(new SuccessMessage(["data"=>$data]),201);
    }
    /**
     * 取消收藏关系
     * @url:/api/v1/collection/cancel
     * @http post
     */
    public function cancelCollection () {
        $validate = new CollectionNew();
        $validate->goCheck();
        //  根据Token获取uid
        $uid = Token::getCurrentUid();
        UserService::isUserExist($uid);
        $data = $validate->getDataByRule(input('post.'));
        CollectionGoodsModel::cancelCollection($data['goods_id'],$uid);
        return json(new SuccessMessage(),201);
    }
    /**
     * 查询用户所有收藏商品
     * @url:/api/v1/collection/my_collections
     * @http get
     */
    public function getAllCollection () {
        //  根据Token获取uid
        $uid = Token::getCurrentUid();
        UserService::isUserExist($uid);
        $collection = CollectionGoodsModel::getCollectionsByUser($uid);
        if($collection->isEmpty()){
            throw new CollectionException(['msg' => '当前用户没有收藏的商品']);
        }else{
            $result = array();
            $collection = $collection->toArray();
            foreach ($collection as $item) {
                $result[] = $item['goods'];
            }
            return $result;
        }
    }
}