<?php

namespace app\api\model;

use app\api\model\BaseModel;
use Exception;

class Goods extends BaseModel
{
    protected $hidden = ['update_time','delete_time','category_id'];
    
    /**
     * 读取器，用于拼接图片地址
     * get+字段名称+Attr
     */
    public function getMainImgUrlAttr($value){
        return $this->prefixImgUrl($value);
    }

    /**
     * 获取收藏关系
     */
    public function collectRelation(){
        return $this->hasMany('CollectionGoods');
    }
    /**
     * 获取图片详情轮播图
     */
    public function banner(){
        //关联模型名称，当前模型外键，关联模型外键
        return $this->belongsTo('Banner','goods_id','goods_id');
    }

    /**
     * 获取商品所属用户
     */
    public function user(){
        //一对一：关联模型名、当前模型外键、关联模型外键
        return $this->belongsTo('User','owner','user_id');
    }

    /**
     * 获取商品详细属性
     */
    public function gooodsProperties(){
        //一对多：关联模型名称、关联模型外键、当前模型主键
        return $this->hasMany('GoodsProperties','goods_id','goods_id');
    }

    /**
     * 查询某一分类商品
     */
    public static function getGoodsByCategoryId($id)
    {
        $goods = self::where('category_id','=',$id)->order('create_time desc')->select();
        return $goods;
    }



    /**
     * 查询某一商品详情
     */
    public static function getOneDetail($goodsID){
        $goods = self::with(['user','banner','banner.bannerImgs','gooodsProperties','gooodsProperties.trade'])
            ->find($goodsID);
        
        return $goods;
    }

    /**
     * 根据商品查询商品基础信息
     */
    public static function getGoodsInfo($goodsID){
        $goods = self::where('goods_id','=',$goodsID)->select();
        return $goods;
    }

    /**
     * 获取指定用户发布的所有商品（未下架）
     */
    public static function getOnGoodsByOwner($uid){
        $goods = self::where('owner','=',$uid)->order('create_time desc')->select();
        return $goods;
    }

    /**
     * 获取指定用户发布的所有商品（已下架）
     */
    public static function getOffGoodsByOwner($uid){
        $goods = self::onlyTrashed()->where('owner','=',$uid)->order('create_time desc')->select();
        return $goods;
    }

    /**
     * 根据商品ID查询所属用户
     */
    public static function findOwner($goodsID){
        $uid = self::where('goods_id','=',$goodsID)->select();
        return $uid;
    }

    /**
     * 查询所有商品
     */
    public static function getAll(){
        $goods = self::order('create_time desc')->select();
        return $goods;
    }

    /**
     * 下架指定商品
     */
    public static function destoryByOwner($goods_id){
        $goods = self::get($goods_id);
        $where = $goods->collectRelation()->select()->visible(['user_id','goods_id']);
        //开始事务
        self::startTrans();
        self::destroy($goods_id,false);
        if(count($where)>0){
            $result = $goods->collectRelation()->where($where)->delete();
            if($result == 0){
                //事务回滚
                self::rollback();
                throw new Exception('批量删除收藏关系失败');
            }else{
                //提交事务
                self::commit();
            }
        }else{
            //  提交事务
            self::commit();
        }
        return true;
    }

    /**
     * 获取商品所属用户的信息
     */
    public static function getOwnerInfoByGoods($goodsID){
        $ownerAddress = self::with(['user','user.address'])->where('goods_id',$goodsID)->select()->visible(['user.address']);
        return $ownerAddress;
    }

}