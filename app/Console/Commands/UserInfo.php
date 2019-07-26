<?php

namespace App\Console\Commands;

use App\Models\Deposit\Merchants;
use App\Models\Deposit\Users;
use App\Models\SyncRecords;
use Illuminate\Console\Command;
use App\Models\UserinfoCount;

class UserInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'userinfo:done';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'userInfo migration';

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
        $this->_merchantInfo();
        $this->_userMigration();
    }

    protected function _merchantInfo(){
        if(env('APP_ENV') == "prd"){
            $db1 = env('PG_DEPOSIT_DATABASE');
            $db2 = env('SR_DEPOSIT_DATABASE');
            $this->_insertMerchants($db1);
            $this->_insertMerchants($db2,'sr_deposit');
        }else{
            $db = env('PG_DEPOSIT_DATABASE');
            $this->_insertMerchants($db);
        }
    }

    protected function _insertMerchants(string $db,string $connection_name='deposit'){
        $table = 'merchants';
        $last_id = SyncRecords::getLastId($db,$table);
        $merchant_model = (new Merchants())->setConnection($connection_name);
        $list = $merchant_model->getMerchantsById($last_id);
        if($list){
            $merchant_last_id = $merchant_model->getMaxId($last_id,$connection_name);
            if(\App\Models\Merchants::insert($list)){
                //失败重试一次
                !SyncRecords::updateLastId($db,$table,$merchant_last_id) && SyncRecords::updateLastId($db,$table,$merchant_last_id);
            }
        }
    }

    protected function _userMigration(){
        if(env('APP_ENV') == "prd"){
            $db1 = env('PG_DEPOSIT_DATABASE');
            $db2 = env('SR_DEPOSIT_DATABASE');
            $this->_insertUsers($db1);
            $this->_insertUsers($db2,'sr_deposit');

        }else{
            $db = env('PG_DEPOSIT_DATABASE');
            $this->_insertUsers($db);
        }
    }
    protected function _insertUsers(string $db,string $connection_name='deposit'){
        $table = 'users';

        $users_model = (new Users())->setConnection($connection_name);
        $flag = true;
        while ($flag){
            $last_id = SyncRecords::getLastId($db,$table);
            $list = $users_model->getList($last_id);
            if($list){
                $user_last_id = $users_model->getMaxId($last_id,$connection_name);
                if(\App\Models\Users::insert($list)){
                    //失败重试一次
                    !SyncRecords::updateLastId($db,$table,$user_last_id) && SyncRecords::updateLastId($db,$table,$user_last_id);
                    //  监测 数据变化  同步进去  todo 手机数也要统计
                    foreach ($list as $v){
                        $user = $v;
                        if($user){
                            $reg_merchant_num = \App\Models\Users::query()->where('cert_no','=',$user['cert_no'])->distinct('merchant_id')->count('merchant_id');
                            $new[]=  [
                                'cert_no'=> $user['cert_no'],
                                'reg_merchant_num'=> $reg_merchant_num
                            ];
                        }
                    }
                    if(!empty($new)){
                        UserinfoCount::insert($new);
                    }
                }
            }else{
                $flag = false;
            }
        }
    }
}