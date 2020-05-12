<?php

namespace app\api\service;

use app\lib\exception\ImageUploadException;
use app\api\model\BannerImg as BannerImgModel;
use app\api\model\Goods as GoodsModel;
use app\api\service\Goods as GoodsService;
use Exception;
use think\Db;

class Banner
{
    protected $base_path;
    protected $user_file;
    protected $save_name;
    protected $goods_id;

    public function uploadBannerImg ($uid,$goodsID) {
        //  指定上传路径基地址为：应用根目录/public/upload
        $this->base_path = str_replace('\\','/',ROOT_PATH . 'public/upload');
        //  指定当前用户上传文件夹：/用户id/bannerImages/日期/
        $this->user_file = "/" . $uid . "/bannerImages/";
        $this->goods_id = $goodsID;
        $this->uid = $uid;

        //验证商品id是否属于当前用户的
        GoodsService::isGoodsOwner($goodsID,$uid);

        //  准备路径
        $this->prepareFilePath();

        $this->saveImages('41943040','jpg,jpeg,png,gif',$this->base_path . $this->user_file);
        $this->createBanner();
    }

    /**
     * 插入失败回滚时删除上传的文件
     */
    public function removeFile () {
        $path = $this->base_path . $this->user_file . $this->save_name;
        if(!file_exists($path)){
            return true;
        }
        if(!unlink($path)) {
            return false;
        }
        return true;
    }

    /**
     * 插入数据
     */
    public function createBanner () {
        //获取图片上传完整路径值（准备插入数据库）
        $url = $this->user_file . $this->save_name;
        $order = request()->param('order');
        //  开始多表插入事务处理
        Db::startTrans();
        try {
            if ($order == 1) {
                GoodsModel::where('goods_id',$this->goods_id)->update(['main_img_url' => $url, 'update_time' => date('Y-m-d H:i:s')]);
            }
            $banner_data = [
                            'goods_id' => $this->goods_id,
                            'create_time' => date('Y-m-d H:i:s')
                        ];
            $banner_id = Db::name('banner')->insertGetId($banner_data);
            $img_data = [
                          'banner_id' => $banner_id,
                          'url' => $url,
                          'order' => $order,
                          'create_time' => date('Y-m-d H:i:s')
                        ];
            BannerImgModel::insert($img_data);
            Db::commit();
        } catch (\Exception $e) {
            $this->removeFile();
            Db::rollback();
            throw Exception($e . '\n' . $this->save_name . '文件删除失败');
        }
    }

    /**
     * 上传图片
     */
    public function saveImages ($size,$ext,$savePath) {
        $file = request()->file('image');
        $info = $file->validate(['size' => $size,'ext' => $ext])->move($savePath);
        if($info){
            $this->save_name = str_replace("\\","/",$info->getSaveName());
        } else {
            throw new ImageUploadException(['msg' => '图片类型错误或超出大小限制']);
        }
    }

    /**
     * 创建图片上传目录
     */
    public function prepareFilePath () {
        $path = $this->base_path . $this->user_file;
        //  判断图片存储路径是否存在
        if(!file_exists($path)){
            $is_success = mkdir($path,0755,true);
            if(!$is_success){
                throw Exception('创建用户上传图片目录失败');
            }
        }
    }
}