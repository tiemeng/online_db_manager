<?php
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/7/25
 * Time: 14:17
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class DbConnection extends Model
{
    protected $table = 'db_connection';

    public static function getList(array $where, int $pageSize = 10)
    {
        return self::where($where)->orderBy('id', 'desc')->paginate($pageSize);
    }

    public static function insertData(array $data)
    {
        return self::insert($data);
    }

    public static function getInfoById(int $id)
    {
        return self::where(['id' => $id])->first();
    }

    public static function updateData(array $data, array $where)
    {
        return self::where($where)->update($data);
    }

    public static function getConnections()
    {
        $list = self::get();
        $connections = "";
        foreach ($list as $key => $item) {
            $str = <<<EOT
        "{$item['conn_name']}"=> [
            'driver' => '{$item['driver']}',
            'host' => '{$item['host']}',
            'port' => '{$item['port']}',
            'database' => '{$item['database']}',
            'username' => '{$item['username']}',
            'password' => '{$item['password']}',
            'charset' => '{$item['charset']}',
            'prefix' => '{$item['prefix']}',
            'strict' => true,
            'engine' => null,
            'schema' => '{$item['schema']}'
        ],\n
EOT;
            $connections .= $str;
        }
        return $connections;
    }

    public static function getDBByDriver(string $driver){
        $data = [];
        $list = self::where(['driver'=>$driver])->select("conn_name","database")->get();
        foreach ($list as $item){
            $data[][$item['database']] = $item['conn_name'];
        }
        return $data;
    }

    public static function getDriverByConn(string $conn_name){
        return self::where(['conn_name'=>$conn_name])->value('driver');
    }
}