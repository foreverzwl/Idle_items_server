<?php

namespace app\api\model;

use app\api\model\BaseModel;

class ChatList extends BaseModel
{
    protected $hidden = ['delete_time'];

    //  关闭update_time字段自动写入
    protected $createTime = false;

    /**
     * 获取消息列表
     */
    public static function getChatListByReceiver($id,$pages,$pageNum){
        $chat = self::order('update_time desc')->where(['receiver_uid' => $id])->whereOr(['uid' => $id])->page($pages,$pageNum)->select()->toArray();
        return $chat;
    }
}