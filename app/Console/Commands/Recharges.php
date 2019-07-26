<?php

namespace App\Console\Commands;

use App\Models\SyncRecords;
use Illuminate\Console\Command;

class Recharges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recharges:done';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'recharges migration';

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

    protected function _migration(){
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

    protected function _insert(string $db,string $connection = 'deposit')
    {
        $len = true;
        $table = 'recharges';
        $recharges_model = (new \App\Models\Deposit\Recharges())->setConnection($connection);
        while ($len){
            $last_id = SyncRecords::getLastId($db,$table);
            $list = $recharges_model->getListById($last_id);
            if($list){
                $payment_last_id = $recharges_model->getMaxId($last_id,$connection);
                if(\App\Models\Recharges::insert($list)){
                    //失败重试一次
                    !SyncRecords::updateLastId($db,$table,$payment_last_id) && SyncRecords::updateLastId($db,$table,$payment_last_id);
                }
            }else{
                $len = false;
            }
        }


    }
}
