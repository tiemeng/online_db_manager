<?php

namespace App\Console\Commands;

use App\Models\SyncRecords;
use Illuminate\Console\Command;

class Credits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'credits:done';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'credits migration';

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
        $this->_migration();
    }

    public function _migration(){
        if(env('APP_ENV') == 'prd'){
            $db = env('PG_DEPOSIT_DATABASE');
            $db1 = env('SR_DEPOSIT_DATABASE');

            $this->_insert($db);
            $this->_insert($db1,'sr_deposit');
        }else{
            $db = env('PG_DEPOSIT_DATABASE');
            $this->_insert($db);
        }
    }

    public function _insert(string $db,string $connection_name='deposit'){
        $len = true;
        $table = 'credits';
        $assets_model = (new \App\Models\Deposit\Credits())->setConnection($connection_name);
        while ($len){
            $last_id = SyncRecords::getLastId($db,$table);
            $list = $assets_model->getCreditsById($last_id);
            if($list){
                $asset_last_id = $assets_model->getMaxId($last_id,$connection_name);
                // 云飞库插入

                if(\App\Models\Credits::insert($list)){
                    //失败重试一次
                    !SyncRecords::updateLastId($db,$table,$asset_last_id) && SyncRecords::updateLastId($db,$table,$asset_last_id);
                }
            }else{
                $len = false;
            }
        }
    }
}
