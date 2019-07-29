<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendMailConsumer extends Command
{
    public static $_host = null;
    public static $_port = null;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendMail:consumer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '申请发送邮件队列';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        self::$_host = env('REDIS_HOST','127.0.0.1');
        self::$_port = env('REDIS_PORT','6379');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Resque::setBackend(self::$_host . ":" . self::$_port);
        $worker = new \Resque_Worker('*');
        // 队列处理时间间隔，单位：秒
        $worker->work(1);
    }
}
