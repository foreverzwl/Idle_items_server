<?php

namespace app\api\validate;

use app\lib\exception\ParameterException;
use app\lib\exception\ValidateException;
use think\Validate;
use think\Request;
use think\Exception;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        //获取所有http传入参数
        $request = Request::instance();
        $params = $request->param();
        $result = $this->batch()->check($params);
        if(!$result){
            $ex = new ValidateException([
                'msg' => $this->getError()
            ]);
            throw $ex;
        }else{
            return true;
        }
    }
    
    protected function isPositiveInteger($value, $rule='', $data='',$field)
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }
        return $field . '必须是正整数';
    }

    protected function isNotEmpty($value, $rule='', $date='', $field){
        if(empty($value)){
            return false;
        }{
            return true;
        }
    }

    protected function isMobile($value){
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule,$value);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 请求参数过滤
     */
    public function getDataByRule($arrays){
        if(array_key_exists('user_id',$arrays)|array_key_exists('uid',$arrays)){
            throw new ParameterException([
                'msg' => '参数中包含非法参数名user_id或uid'
            ]);
        }
        $newArr = [];
        foreach($this->rule as $key => $value){
            $newArr[$key] = $arrays[$key];
        }
        return $newArr;
    }

}