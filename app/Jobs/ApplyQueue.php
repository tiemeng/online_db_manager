<?php

namespace App\Jobs;

use App\Util\Common;

/**
 * sql申请执行发送邮件队列
 * User: tiemeng
 * Date: 2019/6/28
 * Time: 10:51
 */
class ApplyQueue extends BaseJobs
{
    public function perform()
    {
        $email = $this->args['email'] ?? "";
        $msg = $this->args['msg'] ?? "";
        $name = $this->argsp['name'] ?? "";
        if($email && $msg){
            try{
                if(Common::sendEmail($email,$name,$msg)){
                    $this->_flag = true;
                    $this->_id = $this->args['_id'] ?? "";
                    echo $this->_id."处理成功".PHP_EOL;
                }
            }catch(\Exception $e){
                \Log::debug($e->getMessage());
                //TODO:记录错误日志
            }

        }
    }
}