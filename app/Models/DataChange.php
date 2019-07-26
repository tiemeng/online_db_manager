<?php
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/7/22
 * Time: 16:15
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class DataChange extends Model
{
    public $table = "data_change";

    public static function getList(array $where = [], int $pageSize = 15)
    {
        $list =  self::where($where)->orderBy("status","asc")->orderBy('id','desc')->paginate($pageSize);
        foreach($list as &$info){
            $apply_info = Admin::getInfoById($info->apply_uid);
            $audit_info = Admin::getInfoById($info->audit_uid);
            $info['apply_user'] = $apply_info['name'] ?? "";
            $info['audit_user'] = $audit_info['name'] ?? "";
        }
        return $list;
    }

    public static function insertData(array $data){
        return self::insert($data);
    }

    public static function getInfoById(int $id){
        return self::where(['id'=>$id])->first();
    }

    public static function updateData(array $data,array $where){
        return self::where($where)->update($data);
    }

}