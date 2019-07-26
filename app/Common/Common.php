<?php
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2018/12/19
 * Time: 17:33
 */

namespace App\Common;


class Common
{
    /**
     * 生成随机key
     * @return string
     */
    public static function createKey(){
        return "6621".rand(100,999).time();
    }

    /**
     * 生成随机secret
     * @return string
     */
    public static function createSecret()
    {
        return md5("YunFei".uniqid());
    }
}