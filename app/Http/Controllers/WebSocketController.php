<?php
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/7/25
 * Time: 09:33
 */

namespace App\Http\Controllers;


class WebSocketController
{

    protected $lock;
    protected $server;
    protected $addr = "127.0.0.1";
    protected $port = 9502;

    public function start()
    {
        $this->lock = new \swoole_lock(SWOOLE_MUTEX);// 对文件或数组进行锁操作，已达到同步
        $this->server = new \swoole_websocket_server($this->addr, $this->port);// swoole提供的Websocket Server
        $this->server->set(array(
            'daemonize' => 0,
            'worker_num' => 4,
            'task_worker_num' => 10,
            'max_request' => 1000,
            'log_file' => '/data/log/swoole/swoole.log'      // swoole日志路径，必须是绝对路径
        ));
        $this->server->on('open', array($this, 'onOpen'));
        $this->server->on('message', array($this, 'onMessage'));
        $this->server->on('task', array($this, 'onTask'));
        $this->server->on('finish', array($this, 'onFinish'));
        $this->server->on('close', array($this, 'onClose'));
        // 启动服务
        $this->server->start();
    }

    public function onOpen($server, $request)
    {
        $message = array(
            'remote_addr' => $request->server['remote_addr'],
            'request_time' => date('Y-m-d H:i:s', $request->server['request_time'])
        );
        \Log::info(json_encode($message));
    }

    public function onMessage($server, $frame)
    {
        $data = json_decode($frame->data);

        switch ($data->type) {
            case 'init':
            case 'INIT':
                $this->users[$frame->fd] = $data->message;
                // 记录每个链接的信息，同样不要尝试打印出来看，因为你只能看到自己的链接信息
                $message = '欢迎' . $data->message . '加入了聊天室';
                $response = array(
                    'type' => 1,    // 1代表系统消息，2代表用户聊天
                    'message' => $message
                );
                break;
            case 'chat':
            case 'CHAT':
                $message = $data->message;
                $response = array(
                    'type' => 2,    // 1代表系统消息，2代表用户聊天
                    'username' => $this->users[$frame->fd],
                    'message' => $message
                );
                break;
            default:
                return false;
        }
        // 将信息交给task处理
        $this->server->task($response);
    }

    public function onTask($server, $task_id, $from_id, $message)
    {
        var_dump($from_id,$message);
        // 迭代所有的客户端链接，将消息推送过去。（如果你尝试将 $this->server->connections 打印出来，那么你会发现他是空的。但当时用 foreach 去循环时，它确实有用。）
        foreach ($this->server->connections as $fd) {
            $this->server->push($fd, json_encode($message));
        }
        $server->finish('Task' . $task_id . 'Finished' . PHP_EOL);
    }
    public function onClose($server, $fd)
    {
        $username = $this->users[$fd];
        // 释放客户端，利用锁进行同步
        $this->lock->lock();
        unset($this->users[$fd]);
        $this->lock->unlock();

        if( $username ) {
            $response = array(
                'type' => 1,    // 1代表系统消息，2代表用户聊天
                'message' => $username . '离开了聊天室'
            );
            $this->server->task($response);
        }


        \Log::info( $fd . ' disconnected');
    }
}