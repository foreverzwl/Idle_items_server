<?php

namespace app\api\controller\v1;

use app\api\model\Chat as ChatModel;
use app\api\service\Token as Token;
use app\api\service\User as UserService;
use app\api\validate\ChatInitValidate;
use app\api\validate\ChatMessage;
use app\lib\exception\SuccessMessage;
use think\Controller;
use GatewayClient\Gateway;
require_once VENDOR.'GatewayClient/Gateway.php';


class Chat extends Controller
{
    private $uid;
    /**
     * 绑定用户id
     * @url /chat/init
     * @http post
     */
    public function bindUid () {
        $validate = new ChatInitValidate();
        $validate->goCheck();
        $dataArr = $validate->getDataByRule(input('post.'));
        $client_id = $dataArr['client_id'];
        //  根据Token获取uid
        $this->uid = Token::getCurrentUid();
        //  判断当前用户是否存在     
        UserService::isUserExist($this->uid);
        //  绑定
        Gateway::bindUid($client_id,$this->uid);

        //  获取未读状态消息
        $chat = ChatModel::getChatAndChange($this->uid,1,10);
        $result = array_map(function ($item) {
            $temp = [
                'msg_id' => $item['msg_id'], 
                'content' => $item['content'], 
                'is_self' => false,
                'other' => $item['receiver_uid'],
                'create_time' => $item['create_time']
            ];
            if($item['uid'] == $this->uid) {
                $temp['is_self'] = true;
            }
            return $temp;
        },$chat);
        return json(new SuccessMessage(['msg' => '绑定用户成功', 'data' => $result]),201);
    }



    /**
     *  当前用户向某一商品所有者发起聊天
     * @url /chat/send_to_store
     * @HTTP POST
     * @id 商品ID
     */
    public function sendToStore(){
        $validate = new ChatMessage();
        $validate->goCheck();

        //  根据Token获取uid
        $uid = Token::getCurrentUid();
        //  判断当前用户是否存在     
        UserService::isUserExist($uid);

        //  获取信息
        $dataArr = $validate->getDataByRule(input('post.'));
        $client_id = $dataArr['client_id'];
        $receiver = $dataArr['receiver_uid'];
        $message = $dataArr['message'];

        Gateway::$registerAddress = 'www.zwl.com:1238';

        //  对方不在线则将消息存储起来
        $isOnline = Gateway::isUidOnline($receiver);

        if(!$isOnline){
            //  插入等待接收状态的消息
            $chat = ChatModel::saveChat($uid,$receiver,$message,0);
        } else {
            //  插入已经被接收的消息
            $chat = ChatModel::saveChat($uid,$receiver,$message,1);
        }

        //发送消息
        $send = json_encode([
                                'type' => 'tidings',
                                'client_id' => $client_id,
                                'message' => $message
                            ],JSON_UNESCAPED_UNICODE);

        
        Gateway::sendToUid($receiver,$send);

        return json(new SuccessMessage(['msg' => '发送消息成功','data' => $receiver]),201);
    }


    
}