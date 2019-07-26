<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018.12.20
 * Time: 17:04
 */

namespace App\Services;


use App\Common\Common;
use App\Http\Requests\PlatformsRequest;
use App\Models\Merchant;
use App\Models\Platform;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Tests\Controller;

class PlatformService
{
    public  $platModel;

    public function __construct(Platform $platform)
    {
        $this->platModel = $platform;
    }

    public function getAllPlats($request) {
        $params = $request->all();
        if(count($params)) {

            if($request->key != null && $request->name != null) {
                $condition = [
                    ['name','like','%'.$request->name.'%'],
                    ['key','=',$request->key],
                ];
            } elseif($request->key == null) {
                $condition = [
                    ['name','like','%'.$request->name.'%'],
                ];
            }else {
                $condition = [
                    ['key','=',$request->key],
                ];
            }

            return $this->platModel->orWhere($condition)->paginate(20);
        }
        return $this->platModel->paginate(20);
    }

    public function store($request) {
        $params = $request->all();
        $merchant_name = Merchant::getOne(['merchant_id' => isset($params['merchant_id']) ? $params['merchant_id'] :0 ]);
        $data = [
            'key' =>Common::createKey(),
            'secret' => Common::createSecret(),
            'company_name' => $params['company_name'],
            'name' => $params['name'],
            'merchant_id' => $request->merchant_id,
            'notify_url' =>  isset($request->notify_url) ? $request->notify_url :'',
            'status' => $request->status,
        ];
        try{
            if($request->method() == 'PUT') {
                return $this->platModel->where(['id' => $request->id])->update($data);
            }
            return $this->platModel->insert($data);
        }catch (\Exception $e) {
            $error = json_encode($e->getMessage());
            \Log::debug('inertPlatforms'.$error);
        }

    }

    public function destroy($request) {
        try {
            return $res = Platform::destroy($request->id);
        }catch (\Exception $e) {
            $error = json_encode($e->getMessage());
            \Log::debug('deletePlatforms'.$error);
        }
    }

    public static function updatePlatformsStatus($request) {
        $status = $request->status ? 0 : 1;
        $fields = ['status' => $status];
        $condition = ['id' => $request->id];
        return Platform::where($condition)->update($fields);
    }

    public function getOne($request) {
        return $this->platModel->where(['id' => $request->id])->first();
    }

    public static function getAllPlatforms() {
        return Platform::get();
    }

}