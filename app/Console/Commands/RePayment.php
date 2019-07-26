<?php

namespace App\Console\Commands;

use App\Models\Deposit\Repayments;
use App\Models\SyncRecords;
use Illuminate\Console\Command;

class RePayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repayment:done';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'repayment migration';

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
            $this->_insert($db1);
        }else{
            $db = env('PG_DEPOSIT_DATABASE');
            $this->_insert($db);
        }
    }

    protected function _insert(string $db,string $connction_name = 'deposit')
    {
        $len = true;
        $table = 'repayments';
        $repayments_model = (new Repayments())->setConnection($connction_name);
        while ($len){
            $last_id = SyncRecords::getLastId($db,$table);
            $list = $repayments_model->getRePayments($last_id);
            if($list){
                $repayment_last_id = $repayments_model->getMaxId($last_id,$connction_name);
                if(\App\Models\Repayments::insert($list)){
                    //失败重试一次
                    !SyncRecords::updateLastId($db,$table,$repayment_last_id) && SyncRecords::updateLastId($db,$table,$repayment_last_id);
                }
            }else{
                $len = false;
            }
        }


    }
}
