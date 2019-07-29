<?php
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/7/1
 * Time: 10:57
 */

namespace App\Jobs;



/**
 * 队列任务处理基类
 * @package App\Jobs
 */
abstract class BaseJobs
{
    /**
     * 队列唯一ID
     * @var null
     */
    protected $_id = null;

    /**
     * 执行成功与否标识
     * @var bool
     */
    protected $_flag = false;

    /**
     * 业务逻辑处理
     * @return mixed
     */
    abstract function perform();

    /**
     * 更新状态
     * @return int
     */
    public function updateStatus()
    {
        return \DB::table('queue')->where(['uniq_id' => $this->_id])->update(['status' => 2, 'updatetime' => time()]);
    }

    /**
     *  如果处理成功更新数据库状态
     */
    public function __destruct()
    {
        if ($this->_flag) {
            !$this->updateStatus() && $this->updateStatus();
        }
    }

}