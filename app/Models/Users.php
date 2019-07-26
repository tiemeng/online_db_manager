<?php
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2018/12/20
 * Time: 10:38
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Users extends Model
{

    public static function countusers($timestamp ,$where){
        $date  =  date('Y-m-d',$timestamp);
        $date_start   = $date.' 00:00:00';
        $date_end   = $date.' 23:59:59';

        return self::query()->where('created_at','>=', $date_start)
            ->where('created_at','<=', $date_end)->where($where)->count();
    }

}