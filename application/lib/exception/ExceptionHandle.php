<?php

namespace app\lib\exception;

use think\exception\Handle;
use think\Log;
use think\Request;
use Exception;

class ExceptionHandle extends Handle
{
    //code表示自定义请求状态码
    private $code;
    private $msg;
    private $errorCode;
    //返回当前客户端当前请求url路径

    //所有抛出的异常都会由render处理，达到全局异常处理
    public function render(Exception $e){
        if($e instanceof BaseException){
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        }else{
            var_dump(config('app_debug'));
            //根据是否开启debug模式来决定异常抛出json结构还是tp5异常页面
            if(config('app_debug')){
                return parent::render($e);
            }else{
                //服务器端错误
                $this->code = 5000;
                $this->msg = '服务器内部错误';
                $this->errorCode = 999;
                //日志记录
                $this->recordErrorLog($e);
            }
            
        }
        $request = Request::instance();
        $result = [
            'msg' => $this->msg,
            'errorCode' => $this->errorCode,
            'request_url' => $request->url()
        ];
        return json($result,$this->code);
    }

    private function recordErrorLog(Exception $e){
        //初始化日志
        Log::init([
            'type' => 'file',
            'path' => LOG_PATH,
            'level' => ['error']
        ]);
        Log::record($e->getMessage(),'error');
    }
    
}
