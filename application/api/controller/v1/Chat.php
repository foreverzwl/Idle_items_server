<?php

namespace app\api\controller\v1;

use app\api\model\Goods as GoodsModel;
use app\api\service\Token as Token;
use app\api\service\User as UserService;
use app\api\validate\ChatMessage;
use app\api\validate\IDMustBeSignlessInt;
use app\lib\exception\GoodsException;
use think\Controller;
use GatewayClient\Gateway;
require_once VENDOR.'GatewayClient/Gateway.php';


class Chat extends Controller
{
    public function index(){
        return view('index');
    }
    /**
     *  当前用户向某一商品所有者发起聊天
     * @url 
     * @HTTP POST
     * @id 商品ID
     */
    public function sendToMerchants($id){
        (new IDMustBeSignlessInt())->goCheck();
        $validate = new ChatMessage();
        $validate->goCheck();

        //  根据Token获取uid
        $uid = Token::getCurrentUid();
        //  判断当前用户是否存在     
        UserService::isUserExist($uid);

        //根据商品查找用户
        $receiver = GoodsModel::findOwner($id);
        if($receiver->isEmpty()){
            throw new GoodsException([
                'msg' => '商品不存在或商品所属用户不存在'
                ]);
        }else{
            $receiver = $receiver[0]['owner'];
        }
        //  获取信息
        $dataArr = $validate->getDataByRule(input('post.'));
        $client_id = $dataArr['client_id'];
        $message = $dataArr['message'];

        Gateway::$registerAddress = 'www.zwl.com:1238';
        //发送消息
        Gateway::bindUid($client_id,$uid);
        Gateway::sendToUid($receiver,$message);
        return json_encode(["result" => "成功"]);
    }
}