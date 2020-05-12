<?php

namespace app\api\service;
use app\api\model\Order as OrderModel;
use app\api\model\Goods as GoodsModel;
use app\api\model\OrderItem as OrderItemModel;
use app\api\model\UserAddress as UserAddressModel;
use app\lib\exception\OrderException;
use app\lib\exception\ParameterException;
use app\lib\exception\UserException;
use Exception;
use think\Db;

class Order
{
    //订单的商品列表
    protected $oGoodsArr;

    //数据库中取出的商品信息
    protected $goodsArr;

    protected $uid;
    
    protected $store_id;

    public function place($uid,$oGoodsArr){
        //oGoods与goods作对比
        $this->oGoodsArr = $oGoodsArr;
        $this->uid = $uid;
        $this->store_id = $this->getStoreId();

        $this->verify();

        // echo "调用getGoodsByOrder\n";
        $this->goodsArr = $this->getGoodsByOrder($oGoodsArr);
        // echo "调用getOrderStatus\n";
        $status = $this->getOrderStatus();
        if(!$status['pass']){
            $status['order_id'] = -1;
            return $status;
        }

        // echo "调用makeOrderNo\n";
        //  生成订单号
        $orderNo = $this->makeOrderNo();
        // 创建订单
        // echo "调用snapOrder\n";
        $orderSnap = $this->snapOrder($orderNo);
        //生成订单并写入数据库
        // echo "调用createOrder\n";
        $status = $this->createOrder($orderSnap, $orderNo);
        $status['pass'] = true;
        return $status;
    }

    /**
     * 验证买家商家是否同一个
     */
    private function verify () {
        if($this->uid == $this->store_id){
            throw new ParameterException(['msg' => '请勿购买自己的商品']);
        }
    }

    /**
     * 获取商家ID
     */
    private function getStoreId () {
        $result = GoodsModel::findOwner($this->oGoodsArr[0]['goods_id']);
        return $result[0]['owner'];
    }

    /**
     * 生成订单
     */
    private function createOrder($snap,$orderNo){
        $order = new OrderModel();

        $buyer = $this->getBuyerInfo();
        $store = $this->getStoreInfo();

        $order->order_no = $orderNo;
        $order->order_name = $snap['order_name'];
        $order->order_main_img_url = $snap['order_main_img_url'];
        $order->buyer_id = $buyer['user_id'];
        $order->buyer_name = $buyer['name'];
        $order->buyer_mobile = $buyer['mobile'];
        $order->buyer_address = $buyer['address'];
        $order->store_id = $store['user_id'];
        $order->store_name = $store['name'];
        $order->store_mobile = $store['mobile'];
        $order->store_address = $store['address'];
        $order->order_price = $snap['order_price'];
        $order->total_count = $snap['total_count'];
        $order->trade_code = 1;
        $order->status = 0;

        Db::startTrans();
        try {
            $order->save();
            $create_time = $order->create_time;
            $orderItem = new OrderItemModel();
            $orderItem->saveAll($snap['gSnapArr']);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            throw new Exception('创建订单失败，数据库异常');
        }
        return [
            'order_no' => $orderNo,
            'create_time' => $create_time
        ];
    }

    /**
     * 分批插入数据
     */
    private function insertPaginate($array,$num){
        $limit = ceil(count($array) / $num);
        //开始事务
        OrderModel::startTrans();
        for($i=1;$i<=$limit;$i++){
            $offset = ($i-1)*$num;
            $data = array_slice($array,$offset,$num);
            $result = OrderModel::insertAll($data);
            if(!$result){
                //事务回滚
                OrderModel::rollback();
                throw new Exception('批量插入失败');
            }
        }
        //提交事务
        OrderModel::commit();
    }

    /**
     * 生成订单号
     */
    public static function makeOrderNo(){
        $yCode = array('A','B','C','D','E','F','G','H','I','J');
        $orderSn = $yCode[intval(date('Y')) - 2020] . strtoupper(dechex(date('m'))) . date('d') . substr(
                microtime(),2,5) . sprintf('%02d',rand(0,99));
        return $orderSn;
    }

    /**
     * 生成订单快照
     */
    private function snapOrder($orderNo){
        $snap = [
            'order_no' => $orderNo,
            'order_name' => $this->goodsArr[0]['description'],
            'order_main_img_url' => $this->goodsArr[0]['main_img_url'],
            'order_price' => 0,
            'total_count' => 0,
            'trade_code' => '',
            'gSnapArr' => [],
        ];
        if (count($this->goodsArr) > 1) {
            $snap['order_name'] .= '等';
        }

        for ($i = 0; $i < count($this->goodsArr); $i++) {
            $goods = $this->goodsArr[$i];
            $gSnap = $this->snapOrderItem($goods,$orderNo,$goods['count']);
            $snap['order_price'] += $gSnap['total_price'];
            $snap['total_count'] += $gSnap['count'];
            array_push($snap['gSnapArr'],$gSnap);
        }
        return $snap;
    }

    /**
     * 生成订单商品快照
     */
    private function snapOrderItem($goods,$orderNo,$oCount){
        $gSnap = [
            'order_no' => $orderNo,
            'goods_id' => $goods['goods_id'],
            'snap_img' => $goods['main_img_url'],
            'snap_description' => $goods['description'],
            'snap_price' => $goods['price'],
            'count' => $oCount,
            'total_price' => $goods['price'] * $oCount
        ];
        return $gSnap;
    }

    /**
     * 获取商家地址信息
     */
    private function getStoreInfo () {
        $address = UserAddressModel::getAddressByID($this->store_id);
        if(!$address){
            $address = [
                'user_id' => $this->store_id,
                'name' => '',
                'mobile' => '',
                'address' => ''
            ];
        }else{
            $address->toArray();
            $address['address'] = $address['country'] . $address['province'] . $address['city'] . $address['area'] . $address['detail'];
        }
        return $address;
    }

    /**
     * 获取买家地址信息
     */
    private function getBuyerInfo () {
        $address = UserAddressModel::getAddressByID($this->uid);
        if(!$address){
            throw new UserException([
                'msg' => '买家用户地址不存在，下单失败',
                'errorCode' => 6001
            ]);
        }
        $address->toArray();
        $address['address'] = $address['country'] . $address['province'] . $address['city'] . $address['area'] . $address['detail'];
        return $address;
    }

    /**
     * 对比数据库商品信息与订单商品信息，判断是否能创建订单
     */
    private function getOrderStatus(){
        $status = [
            'pass' => true,
            'order_price' => 0,
            'trade_code' => 1,
            'gStatusArr' => []
        ];
        foreach($this->oGoodsArr as $oGoods){
            $gStatus = $this->getGoodsStatus($oGoods['goods_id'],$oGoods['count'],$this->goodsArr);

            //  有一个商品的库存不足则订单状态为不能创建
            if(!$gStatus['haveStock']){
                $status['pass'] = false;
            }
            $status['order_price'] += $gStatus['total_price'];
            array_push($status['gStatusArr'],$gStatus);
        }
        return $status;
    }

    /**
     * 进行单个商品信息比对（检测库存）
     */
    private function getGoodsStatus($oGID,$oCount,$goodsArr){
        $gIndex = -1;
        $gStatus = [
            'goods_id' => '',
            'haveStock' => false,
            'count' => 0,
            'description' => '',
            'main_img_url' => '',
            'price' => '',
            'total_price' => 0
        ];

        //  判断商品是否存在
        for($i=0;$i<count($goodsArr);$i++){
            if($oGID == $goodsArr[$i]['goods_id']){
                $gIndex = $i;
            }
        }
        if($gIndex == -1){
            throw new OrderException([
                'msg' => 'ID为'.$oGID.'的商品不存在，创建订单失败'
                ]);
        }else{

            //  将购买数量加在$this->goodsArr上
            $this->goodsArr[$gIndex]['count'] = $oCount;

            $goods = $goodsArr[$gIndex];
            $gStatus['goods_id'] = $goods['goods_id'];
            $gStatus['description'] = $goods['description'];
            $gStatus['main_img_url'] = $goods['main_img_url'];
            $gStatus['price'] = $goods['price'];
            $gStatus['count'] = $oCount;
            $gStatus['total_price'] = $goods['price']*$oCount;
            $gStatus['haveStock'] = ($goods['stock']-$oCount)>=0?true:false;
        }
        return $gStatus;
    }
    
    /**
     * 根据订单信息查找商品
     */
    private function getGoodsByOrder($oGoodsArr){
        $oGIDs = [];
        foreach($oGoodsArr as $item){
            array_push($oGIDs,$item['goods_id']);
        }
        $goodsArr = GoodsModel::all($oGIDs)
            ->visible(['goods_id','price','stock','description','main_img_url'])
            ->toArray();
        return $goodsArr;
    }

}