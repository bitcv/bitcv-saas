<?php

namespace App\Exceptions;

/**
 * model层统一日志处理，如果想再细分抛错信息，可以再抽象
 * Class Model
 * @package App\Exceptions
 */
class Model extends \Exception {

    public $file;

    public function __construct($message = "", $code = 0, \Exception $previous = null) {
        $this->file = '../storage/logs/model/'.date('Y-m-d').'.php';
        $user_msg = null;
        if(strpos($message, "error.") === 0){
            if (!$error = \Config::get($message)) {
                $msg = $message;
            }else {
                $user_msg   = $error['user_msg'];
                $msg        = $error['msg'];
                $code       = $error['retcode'];
            }
        }else{
            $msg = $message;
        }

        $this->file($code, $msg, $user_msg);
        parent::__construct($user_msg?$user_msg:$msg, $code, $previous);
    }

    //todo 后期可以优化日志格式，做业务日志分析
    public function file($code, $msg, $user_msg) {
        $message = array(date('Y-m-d H:i:s'), \Route::current()->getActionName(), $code, $msg, $user_msg, self::getFile(), self::getLine(), json_encode(self::getTraceAsString()));

        $message = implode(" | ", $message);

        if (!is_dir(dirname($this->file))) {
            mkdir(dirname($this->file), 0777);
        }

        file_put_contents($this->file, $message.PHP_EOL, FILE_APPEND);
    }
}