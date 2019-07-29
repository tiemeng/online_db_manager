<?php

namespace App\Console\Commands;

use App\Util\Common;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
//        Common::applyNotice('tiemeng19880211@163.com','test','test');
        $tables = [
            'actions_logs' => "日志记录",
            'queue' => "队列信息表",
        ];
        $tbinfo = [
            'actions_logs' => [
                [
                    'column_name' => 'id',
                    'column_type' => 'int',
                    'column_key' => 'PRI',
                    'is_nullable' => 'YES',
                    'COLUMN_default' => '',
                    'column_comment' => '',
                ],
                [
                    'column_name' => 'admin_id',
                    'column_type' => 'int',
                    'column_key' => '',
                    'is_nullable' => 'YES',
                    'COLUMN_default' => '0',
                    'column_comment' => '管理员ID',
                ]
            ],
            'queue'=>[
                [
                    'column_name' => 'id',
                    'column_type' => 'int',
                    'column_key' => 'PRI',
                    'is_nullable' => 'YES',
                    'COLUMN_default' => '',
                    'column_comment' => '',
                ],
                [
                    'column_name' => 'title',
                    'column_type' => 'varchar(20)',
                    'column_key' => '',
                    'is_nullable' => 'YES',
                    'COLUMN_default' => '0',
                    'column_comment' => '队列名称',
                ]
            ]
        ];
        Common::generateWord($tables,$tbinfo);
    }
}
