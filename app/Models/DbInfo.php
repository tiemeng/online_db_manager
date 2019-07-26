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

    public function __construct(string $connection, array $attributes = [])
    {
        \Config::set('database.connections.' . $connection . '.database', 'information_schema');
        $this->_db = \DB::connection($connection);
        parent::__construct($attributes);
    }

    public function getTablesByDb(string $db)
    {

        $list = $this->_db->table("tables")->where(['table_schema' => $db])->select([
            'table_name'
        ])->get()->toArray();
        return array_column($list, "table_name");
    }

    public function getDbsByConnection(array $where = [])
    {
        $dbs = $this->_db->table('SCHEMATA')
            ->where($where)
            ->where("schema_name", "!=", "information_schema")
            ->where("schema_name", "!=", "performance_schema")
            ->where('schema_name', '!=', 'sys')
            ->where('schema_name', '!=', 'mysql')->select([
                'SCHEMA_NAME',
                'DEFAULT_CHARACTER_SET_NAME',
                'DEFAULT_COLLATION_NAME'
            ])->paginate(10);
        return $dbs;
    }

    public function getTables(string $db)
    {
        return $this->_db->table("tables")->where(['table_schema' => $db])->select([
            'table_name',
            'table_comment'
        ])->get()->toArray();
    }

    public function tablesInfo(string $db, string $table)
    {
        return $this->_db->table("COLUMNS")->where([
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
}