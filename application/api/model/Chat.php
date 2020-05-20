<?php

namespace app\api\model;

use app\api\model\BaseModel;
use app\api\model\ChatList as ChatListModel;
use Exception;
use think\Db;

class Chat extends BaseModel
{
    protected $hidden = ['delete_time'];

    //  关闭update_time字段自动写入
    protected $updateTime = false;
    
    protected static $uid;

    /**
     * 存储聊天记录
     */
    public static function saveChat($uid,$receiver,$content,$status){
        Db::startTrans();
        try {
            $chatListModel = new ChatListModel();
            $isExist = $chatListModel::where(['uid' => $uid, 'receiver_uid' => $receiver])->find();
            if(!$isExist){
                $chatListModel->save(['uid' => $uid, 'receiver_uid' => $receiver]);
            }else {
                $chatListModel->isUpdate()->save(['uid' => $uid, 'receiver_uid' => $receiver]);
            }
            $chat = self::create(['uid' => $uid, 'receiver_uid' => $receiver, 'content' => $content, 'status' => $status]);
        } catch (\Exception $e) {
            Db::rollback();
            throw new Exception($e);
        }
        return $chat;
    }

    /**
     * 获取未读消息并设置成已读取
     */
    public static function getChatAndChange($id,$pages,$pageNum){
        self::$uid = $id;
        $chat = self::order('create_time desc')->where(['receiver_uid' => $id])->whereOr(['uid' => $id])->page($pages,$pageNum)->select()->toArray();
        $result = array_map(function ($item) {
            if($item['receiver_uid'] == self::$uid){
                return ['msg_id' => $item['msg_id'], 'status' => 1];
            }else{
                return [];
            }
        },$chat);
        $chatModel = new Chat();
        $chatModel->isUpdate(true)->saveAll($result);
        return $chat;
    }


    
}