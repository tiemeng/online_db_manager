<?php

namespace App\Console\Commands;

use App\Models\SyncRecords;
use App\Models\UserinfoCount;
use App\Models\Users;
use Illuminate\Console\Command;

class BindCards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bind_card:done';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'binding card migration';

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
        $table = 'binding_cards';
        $model = (new \App\Models\Deposit\BindingCards())->setConnection($connection_name);
        while ($len){
            $last_id = SyncRecords::getLastId($db,$table);
            $list = $model->getAssetsById($last_id);
            if($list){
                $asset_last_id = $model->getMaxId($last_id,$connection_name);
                if(\App\Models\BindingCards::insert($list)){
                    //失败重试一次
                    !SyncRecords::updateLastId($db,$table,$asset_last_id) && SyncRecords::updateLastId($db,$table,$asset_last_id);
                    //  监测 数据变化  同步进去  todo 手机数也要统计
                    foreach ($list as $v){
                        $user = Users::query()->where('card_no','=',$v['card_no'])->first(['cert_no']);
                        if($user){
                            $bankcard_num = Users::query()->join('binding_cards','binding_cards.card_no','=','users.card_no')->where('cert_no','=',$user['cert_no'])->distinct('bank_card_no')->count('bank_card_no');
                            $new[]=  [
                                'cert_no'=> $user['cert_no'],
                                'bankcard_num'=> $bankcard_num
                            ];
                        }
                    }
                    if(!empty($new)){
                        UserinfoCount::updateBatch($new);
                    }
                }
            }else{
                $len = false;
            }
        }
    }
}
