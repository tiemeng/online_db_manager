<?php
namespace App\Services;

use App\Models\BlackUser;
use App\Models\BlackPlatform;

class BlackListService
{
    protected  $BlackUser;

    public function __construct(BlackUser $BlackUser)
    {
        $this->BlackUser = $BlackUser;
    }

    public function getBlackListUser($request)
    {
        $params = $request->all();

        $condition = [];
        if(!empty($params['name'])){
            $condition[] = [
                'black_users.name','=',$params['name']
            ];
        }
        if(!empty($params['mobile'])){
            $condition[] = [
                'mobile','=',$params['mobile']
            ];
        }
        if(!empty($params['device_no'])){
            $condition[] = [
                'device_no','=',$params['device_no']
            ];
        }
        if(!empty($params['cert_no'])){
            $condition[] = [
                'cert_no','=',$params['cert_no']
            ];
        }
        return $this->BlackUser->select(['black_users.*','platforms.name as pname'])->orWhere($condition)->leftjoin('platforms','platforms.id','=','black_users.platform_id')->orderBy('id','desc')->paginate(15);
    }

    public function getBlackListDetail($id){
        return BlackPlatform::where('black_id','=',$id)->paginate(15);
    }

    public function borrowAmount($id){
        return BlackPlatform::where('black_id','=',$id)->sum('borrowing');
    }

    public function create($request){
        $data = $request->all();
        $params['platform_id'] = $data['platform_id'];
        $params['name'] = $data['name'];
        $params['cert_no'] = $data['cert_no'];
        $params['mobile'] = $data['mobile'];
        $params['device_no'] = $data['device_no'];
        $params['ip'] = $data['ip'];
        $params['created_at'] = date("Y-m-d H:i:s",time());

        try{
            $black_id = $this->BlackUser->insertGetId($params);
            $result = [];
            foreach ($data['platform_name'] as $key => $val){
                $result[$key]['black_id'] = $black_id;
                $result[$key]['platform_name'] = $val;
                $result[$key]['borrowing'] = $data['borrowing'][$key];
                $result[$key]['investment'] = $data['investment'][$key];
                $result[$key]['status'] = $data['status'][$key];
                $result[$key]['created_at'] = date("Y-m-d H:i:s",time());
            }
            return BlackPlatform::insert($result);
        }catch (\Exception $e) {
            $error = json_encode($e->getMessage());
            \Log::debug('BlackList_insert'.$error);
            return FALSE;
        }
    }

    public function batchInsert($request,$data){
        unset($data[0]);

        try{
            foreach ($data as $key => $val){
                $params = [];
                $params['platform_id'] = $request->platform_id;
                $params['name'] = $val[0];
                $params['cert_no'] = $val[1];
                $params['mobile'] = $val[2];
                $params['device_no'] = $val[3];
                $params['ip'] = $val[4];
                $params['created_at'] = date("Y-m-d H:i:s",time());
                $black_id = $this->BlackUser->insertGetId($params);

                $result = json_decode($val[5],TRUE);
                foreach ($result as $k => $v){
                    $result[$k]['black_id'] = $black_id;
                    $result[$k]['created_at'] = date("Y-m-d H:i:s",time());
                }
                BlackPlatform::insert($result);
            }
            return ture;
        }catch (\Exception $e) {
            $error = json_encode($e->getMessage());
            \Log::debug('BlackList_batch'.$error,$data);
            return FALSE;
        }
    }

}