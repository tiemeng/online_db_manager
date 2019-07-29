<?php
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/7/29
 * Time: 10:21
 */

namespace App\Util;


class ReQueue
{
    /**
     * redis 地址
     * @var string
     */
    public static $_host = null;
    /**
     * redis 端口
     * @var string
     */
    public static $_port = null;
    /**
     * 队列名称
     * @var string
     */
    protected $_queueName = 'default';
    /**
     * 任务命名空间
     * @var string
     */
    private $_classPrefix = "\\App\\Jobs\\";
    /**
     * 任务处理类名
     * @var null
     */
    protected $_className = null;
    /**
     * 队列数据
     * @var null
     */
    protected $_data = null;

    /**
     * 队列唯一ID
     * @var null
     */
    protected static $_uniqueId = null;

    /**
     * 获取队列实例
     * @return ReQueue
     * @throws \Exception
     */
    public static function getInstance()
    {
        try {
            self::$_uniqueId = uniqid();
            self::$_host = env('REDIS_HOST', '127.0.0.1');
            self::$_port = env('REDIS_PORT', '6379');
            \Resque::setBackend(self::$_host . ":" . self::$_port);
            return new self();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }

    /**
     * 设置队列名称
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->_queueName = $name;
        return $this;
    }

    /**
     * 设置处理类名
     * @param string $name
     * @return $this
     */
    public function setClassName(string $name)
    {
        if (!is_null($name)) {
            $this->_className = $this->_classPrefix . $name;
        }

        return $this;
    }

    /**
     * 添加数据
     * @param $data
     * @return $this
     */
    public function setData(array $data)
    {
        if(empty($data)){
            return $this;
        }
        $data['_id'] = self::$_uniqueId;
        $this->_data = $data;
        return $this;
    }

    /**
     * 执行入队
     * @return string
     * @throws \Exception
     */
    public function run()
    {
        if (is_null($this->_className) || empty($this->_className)) {
            throw new \Exception("handle class not exists");
        }
        if (is_null($this->_data) || empty($this->_data)) {
            throw new \Exception("data is not empty");
        }
        try {
            $id = \Resque::enqueue($this->_queueName, $this->_className, $this->_data, true);
            if ($id) {
                !\DB::table('queue')->insert([
                    'uniq_id' => self::$_uniqueId,
                    'queue_id' => $id,
                    'clname' => $this->_className,
                    'queue_name' => $this->_queueName,
                    'data' => json_encode($this->_data),
                    'create_time' => time()
                ]) && \DB::table('queue')->insert([
                    'uniq_id' => self::$_uniqueId,
                    'queue_id' => $id,
                    'clname' => $this->_className,
                    'queue_name' => $this->_queueName,
                    'data' => json_encode($this->_data),
                    'create_time' => time()
                ]);
            }
            return $id;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }
}