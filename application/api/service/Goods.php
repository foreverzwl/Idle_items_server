<?php

namespace app\api\service;

use app\api\model\Goods as GoodsModel;
use app\lib\exception\GoodsException;
use app\lib\exception\ScopeException;
use Exception;
use think\Db;

class Goods
{
    protected $uid;
    protected $goods;
    protected $goods_properties;
    
    /**
     * 判断操作用户是否为物品所有者
     * @return true
     */
    public static function isGoodsOwner($id,$uid){
        $owner = GoodsModel::getGoodsInfo($id);
        if($owner->isEmpty()){
            throw new GoodsException();
        }
        if($owner[0]['owner'] != $uid){
            throw new ScopeException();
        }
        return true;
    }

    /**
     * 创建商品
     */
    public function createMyGoods ($uid,$goodsData) {
        $this->uid = $uid;
        $this->goods = $goodsData['goods'];
        $this->goods_properties = $goodsData['goods_properties'];
        //  多表插入事务处理
        Db::startTrans();
        $goods_data = $this->goods;
        $goods_data['owner'] = $uid;
        $goods_data['create_time'] = date('Y-m-d H:i:s');
        $goods_id = Db::name('goods')->insertGetId($goods_data);
        if (empty($goods_id)) {
            Db::rollback();
            throw new Exception ('插入商品信息失败');
        }
        $properties_data = $this->goods_properties;
        $properties_data['goods_id'] = $goods_id;
        $properties_data['create_time'] = date('Y-m-d H:i:s');
        $result = Db::name('goods_properties')->insert($properties_data);
        if (!$result) {
            Db::rollback();
            throw new Exception ('插入商品基本属性信息失败');
        }
        Db::commit();
        return $goods_id;
    }   
}