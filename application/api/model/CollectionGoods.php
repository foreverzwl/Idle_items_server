<?php

namespace app\api\model;

use app\api\model\BaseModel;

class CollectionGoods extends BaseModel
{
    protected $hidden = ['user_id','delete_time'];
    //  关闭update_time字段自动写入
    protected $updateTime = false;

    public function goods () {
        return $this->belongsTo('Goods','goods_id','goods_id')->field('goods_id,description,price,main_img_url');
    }

    /**
     * 查询收藏关系
     */
    public static function getCollection ($goodsID,$uid) {
        $collection = self::where(['goods_id'=>$goodsID,'user_id'=>$uid])->select();
        return $collection;
    }

    /**
     * 删除收藏关系
     */
    public static function cancelCollection ($goodsID,$uid) {
        $result = self::where(['goods_id'=>$goodsID,'user_id'=>$uid])->delete(true);
        return $result;
    }

    /**
     * 获取所有收藏的商品
     */
    public static function getCollectionsByUser ($uid) {
        $result = self::all(['user_id'=>$uid],'goods');
        return $result;
    }
}
