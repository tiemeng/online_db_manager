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

    public function __construct(string $connection, $db = 'information_schema', array $attributes = [])
    {
        \Config::set('database.connections.' . $connection . '.database', $db);
        $this->_db = \DB::connection($connection);
        parent::__construct($attributes);
    }

    /**
     * 通过数据库获取所有的表
     * @param string $db
     * @return array
     */
    public function getTablesByDb(string $db)
    {

        $list = $this->_db->table("tables")->where(['table_schema' => $db])->select([
            'table_name'
        ])->get()->toArray();
        return array_column($list, "table_name");
    }

    /**
     * 通过连接名获取所有的数据库
     * @param array $where
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
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

    /**
     * 获取所有的表明
     * @param string $db
     * @return array
     */
    public function getTables(string $db)
    {
        return $this->_db->table("tables")->where(['table_schema' => $db])->select([
            'table_name',
            'table_comment'
        ])->get()->toArray();
    }

    /**
     * 获取表的结构
     * @param string $db
     * @param string $table
     * @return \Illuminate\Support\Collection
     */
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

    /**
     * 执行相对应的sql
     * @param string $sql
     * @return bool
     * @throws \Exception
     */
    public function exec(string $sql)
    {
        try {
            return $this->_db->statement($sql);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}