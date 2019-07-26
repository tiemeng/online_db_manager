<?php
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/7/25
 * Time: 17:25
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class DbInfo extends Model
{
    protected $_db = null;
    public function __construct(string $connection,array $attributes = [])
    {
        \Config::set('database.connections.'.$connection.'.database','information_schema');
        $this->_db = \DB::connection($connection);
        parent::__construct($attributes);
    }

    public function getTablesByDb(string $db){

        $list =  $this->_db->table("tables")->where(['table_schema' => $db])->select([
            'table_name'
        ])->get()->toArray();
        return array_column($list,"table_name");
    }
}