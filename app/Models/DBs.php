<?php
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/7/22
 * Time: 13:33
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class DBs extends Model
{
    public static function getDBs(array $where)
    {
        if(empty($where['schema_name'])){
            $where = [];
        }
        $dbs = self::_getConnection()->table('SCHEMATA')
            ->where($where)
            ->where("schema_name", "!=", "information_schema")
            ->where('schema_name', '!=', 'sys')
            ->where('schema_name', '!=', 'mysql')->select([
            'SCHEMA_NAME',
            'DEFAULT_CHARACTER_SET_NAME',
            'DEFAULT_COLLATION_NAME'
        ])->paginate(10);
        return $dbs;
    }

    public static function getTables(string $db)
    {
        return self::_getConnection()->table("tables")->where(['table_schema' => $db])->select([
            'table_name',
            'table_comment'
        ])->get()->toArray();
    }

    public static function tablesInfo(string $db, string $table)
    {
        return self::_getConnection()->table("COLUMNS")->where([
            'table_schema' => $db,
            'table_name' => $table
        ])->select([
            "column_name",
            "column_type",
            "column_key",
            "is_nullable",
            "COLUMN_default",
            "column_comment"
        ])->get();
    }

    protected static function _getConnection()
    {
        return \DB::connection('information');
    }
}