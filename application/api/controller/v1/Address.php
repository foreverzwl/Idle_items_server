<?php 

namespace app\api\controller\v1;

use app\api\validate\AddressNew;
use app\api\service\Token as Token;
use app\api\model\User as UserModel;
use app\api\model\UserAddress as UserAddressModel;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;

class Address extends BaseController
{
    private $user;
    private $uid;

    /**
     * 为当前控制器中的方法设置前置方法,''表示为全部方法执行前置操作
     */
    protected $beforeActionList = [
        'verifyUser' => ''
    ];

    /**
     * 从token获取用户并验证用户是否存在
     */
    protected function verifyUser () {
        //  根据Token获取uid
        $uid = Token::getCurrentUid();
        $this->uid = $uid;

        //  根据uid查找用户数据，判断用户是否存在
        $user = UserModel::get($uid);
        $this->user = $user;
    }

    /**
     * 新增或修改用户收货地址
     * @url /api/v1/address/operate_address
     * $http GET
     */
    public function createOrUpdateAddress(){
        // 验证地址参数
        $validate = new AddressNew();
        $validate->goCheck();

        if(!$this->user){
            throw new UserException();
        }
        //  获取并过滤请求参数
        $dataArr = $validate->getDataByRule(input('post.'));
        //  判断用户地址是否存在，不存在则添加，存在则更新
        $userAddress = UserModel::get($this->uid)->address;
        if(!$userAddress){
            //  通过模型的关联属性新增记录
            $this->user->address()->save($dataArr);
        }else{
            //  更新操作
            $this->user->address->save($dataArr);
        }
        return json(new SuccessMessage(),201);
    }

    /**
     * 获取用户地址信息
     * @url /api/v1/address
     * $http get
     */
    public function getAddress () {
        $address = UserAddressModel::getAddressByID($this->uid);
        if (!$address) {
            throw new UserException(['msg' => '当前用户没有地址信息']);
        }
        $address->address = $address->province . $address->city . $address->area . $address->detail;
        return $address;
    }
}


