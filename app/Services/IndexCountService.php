<?php


namespace App\Services;

use App\Console\Commands\RePayment;
use App\Models\Assets;
//use App\Models\Deposit\ActiveMembers;
use App\Models\BlackUser;
use App\Models\Credits;
use App\Models\Payments;
use App\Models\Platforms;
use App\Models\Recharges;
use App\Models\Repayments;
use App\Models\Users;
use App\Models\Withdraws;
use Auth;
use Route;
use Zhuzhichao\IpLocationZh\Ip;
use App\Repositories\RulesRepository;
use App\Repositories\ActionLogsRepository;


class IndexCountService
{
    protected $rulesRepository;

    protected $actionLogsRepository;


    /**
     * 获取首页统计数据
     * @return mixed
     */
    public function getIndexCount()
    {
        $date  =  date('Y-m-d');
        $date_start   = $date.' 00:00:00';
        $date_end   = $date.' 23:59:59';

        $recharge_sum  = Recharges::query()->sum('amount');
        $withdraw_sum  = Withdraws::query()->sum('amount');
        $user_area1 =  \DB::select('select user_province,count(*) as num from users GROUP BY  user_province');


        $data  = [
            'today_reg_total'=> Users::query()->where('created_at','>=', $date_start)
                ->where('created_at','<=', $date_end)->count(),
            'today_payment_total'=>   Payments::query()->where('created_at','>=', $date.' 00:00:00')
                ->where('updated_at','<=', $date.' 23:59:59')->sum('amount'),
            'today_repayment_total'=>   Repayments::query()->where('created_at','>=', $date.' 00:00:00')
                ->where('updated_at','<=', $date.' 23:59:59')->sum('amount'),
            'history_transaction_total'=> $recharge_sum+$withdraw_sum,  // 充值提现额 加
            'today_active_member_num'=> \DB::connection('statistics')->table('deposit_active_members')->where('active_at','>=', $date_start)
                ->where('active_at','<=', $date_end)->distinct('e_account_no')->count(),

            'platform_total'=> Platforms::query()->where('status','=', 1)->count(),

            'loan_total'=> Assets::query()->whereIn('flag',[3,5])->count(), // 累计借款
            'loan_user_total'=> 0,  // 累计借款人数
            'loaning_total'=> Assets::query()->whereIn('flag',[3])->count(),  // 在贷 笔数
            'loaning_user_total'=> 0,  //在贷人数

            'history_reg_total'=> Users::query()->count(),   //累计注册 人数
            'history_buy_user_total'=>  Credits::query()->where('flag','<>',5)->distinct('creditor_card_no')->count('creditor_card_no'),  // 累计投资人数
            'unrepayment_buy_user_total'=> Credits::query()->where('flag','=',2)->distinct('creditor_card_no')->count('creditor_card_no'),  // 出借人 在偿人数
            'reg_creditor'=> [
                Users::countusers(   time(),['role_type'=> 1] ),
                Users::countusers(   time()-1*86400,['role_type'=> 1] ),
                Users::countusers(   time()-2*86400,['role_type'=> 1] ),
                Users::countusers(   time()-3*86400,['role_type'=> 1] ),
                Users::countusers(   time()-4*86400,['role_type'=> 1] ),
                Users::countusers(   time()-5*86400,['role_type'=> 1] ),
                Users::countusers(   time()-6*86400,['role_type'=> 1] ),
            ],
            'reg_debitor'=> [
                Users::countusers(   time(),['role_type'=> 0] ),
                Users::countusers(   time()-1*86400,['role_type'=> 0] ),
                Users::countusers(   time()-2*86400,['role_type'=> 0] ),
                Users::countusers(   time()-3*86400,['role_type'=> 0] ),
                Users::countusers(   time()-4*86400,['role_type'=> 0] ),
                Users::countusers(   time()-5*86400,['role_type'=> 0] ),
                Users::countusers(   time()-6*86400,['role_type'=> 0] ),
            ],
            'payments_info'=> [
                $this->paymentsCount(),
                $this->paymentsCount(1),
                $this->paymentsCount(2),
                $this->paymentsCount(3),
            ],
            'today_black_user'=> BlackUser::query()->where('created_at','>=', $date_start)
                ->where('created_at','<=', $date_end)->count(),  // 今日黑名单人数
            'history_black_user'=> BlackUser::query()->count(),  // 历史黑名单人数
            //todo 逾期  暂时用统计表  非精准数据  后期建立自己的表
            'today_overdue_money'=> \DB::connection('statistics')->table('deposit_data_monitor_day')->where('created_at','>=', $date_start)->where('created_at','<=', $date_end)->where(['type'=>12])->sum('money'),
            'history_overdue_money'=> \DB::connection('statistics')->table('deposit_data_monitor_day')->where(['type'=>12])->sum('money'),
            'user_area'=> [
                json_decode(json_encode($user_area1),true  )


        ]


        ];
        return $data;
    }
    /**
     * 统计放款还款笔数和金额
     * @param $type 0 全部  1 年 2 月 3周
     * @return array
    */
    public function paymentsCount( $type = 0 ){
        $time_end = date('Y-m-d H:i:s');
        switch ($type){
            case 1:
                $time_start =   date('Y-m-d H:i:s',time()-86400*30*12 );
                break;
            case 2:
                $time_start =   date('Y-m-d H:i:s',time()-86400*30 );
                break;
            case 3:
                $time_start =   date('Y-m-d H:i:s',time()-86400*7 );
                break;
            default:
                $time_start =   date('Y-m-d H:i:s',1 );

        }
        $re = [
            Payments::query()->where('created_at','<=',$time_end)->where('created_at','>=',$time_start)->count(),
            Payments::query()->where('created_at','<=',$time_end)->where('created_at','>=',$time_start)->sum('amount'),
            Repayments::query()->where('created_at','<=',$time_end)->where('created_at','>=',$time_start)->count(),
            Repayments::query()->where('created_at','<=',$time_end)->where('created_at','>=',$time_start)->sum('amount'),
        ] ;
        return $re;
    }
}