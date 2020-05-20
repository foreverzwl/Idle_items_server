<?php

namespace app\api\controller\v1;

use app\api\model\ChatList as ChatListModel;
use app\api\model\UserAddress as  UserAddressModel;
use app\api\service\User as UserService;
use app\api\service\Token as Token;

use think\Controller;

class ChatList extends Controller
{

    protected $uid;

    /**
     * 获取聊天列表
     * @url /chatList/:i
     * @http get
     */
    public function getChatList($i=1){
        //  根据Token获取uid
        $this->uid = Token::getCurrentUid();
        //  判断当前用户是否存在     
        UserService::isUserExist($this->uid);
        

        $chat = ChatListModel::getChatListByReceiver($this->uid,$i,10);
        if(empty($chat)){
            return $chat;
        }
        $otherId = array();
        $result = array();
        for($j =0; $j<count($chat); $j++){
            $temp = array();
            $item = $chat[$j];
            if($item['uid'] == $this->uid) {
                $temp['myself'] = $item['uid'];
                $temp['other'] = $item['receiver_uid'];
                $temp['create_time'] = $item['update_time'];
            }else{
                $temp['myself'] = $item['receiver_uid'];
                $temp['other'] = $item['uid'];
                $temp['create_time'] = $item['update_time'];
            }
            array_push($otherId,$temp['other']);
            array_push($result,$temp);
        }
        $user = UserAddressModel::where('user_id','in',$otherId)->select()->visible(['user_id','name'])->toArray();
        for($k =0;$k<count($result); $k++){
            if($result[$k]['other'] == $user[$k]['user_id']){
                $result[$k]['name'] = $user[$k]['name'];
            }else{
                $result[$k]['name'] = '';
            }
        }
        return $result;
    }

}