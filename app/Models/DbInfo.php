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
    private $_drive = null;

    public function __construct(string $connection, $db = null, array $attributes = [])
    {
        $this->_drive = \Config::get('database.connections.' . $connection . '.driver');
        if ($db == null) {
            if ($this->_drive == 'mysql') {
                $db = 'information_schema';
            } else {
                $db = 'postgres';
            }
        }

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
        if ($this->_drive == "mysql") {
            $list = $this->_db->table("tables")->where(['table_schema' => $db])->select([
                'table_name'
            ])->get()->toArray();
        } else {
            $list = $this->_db->select("SELECT
a.relname AS table_name
FROM pg_class a
WHERE a.relnamespace=(SELECT oid FROM pg_namespace WHERE nspname='public')
AND a.relkind='r'
ORDER BY a.relname");
        }

        return array_column($list, "table_name");
    }

    /**
     * 通过连接名获取所有的数据库
     * @param array $where
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getDbsByConnection(array $where = [])
    {
        if ($this->_drive == 'mysql') {
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
        } else {
            $dbs = $this->_db->table('pg_database')
                ->whereNotIn('datname',
                    ['template0', 'template1', 'postgres'])
                ->select([
                    'datname as SCHEMA_NAME',
                    'datcollate as DEFAULT_CHARACTER_SET_NAME',
                    'datctype as DEFAULT_COLLATION_NAME'
                ])->paginate(10);
        }

        return $dbs;
    }

    /**
     * 获取所有的表名字
     * @param string $db
     * @return array
     */
    public function getTables(string $db)
    {
        if ($this->_drive == 'mysql') {
            return $this->_db->table("tables")->where(['table_schema' => $db])->select([
                'table_name',
                'table_comment'
            ])->get()->toArray();
        } else {
            return $this->_db->select("SELECT
a.relname AS table_name,
b.description AS table_comment
FROM pg_class a
LEFT OUTER JOIN pg_description b ON b.objsubid=0 AND a.oid=b.objoid
WHERE a.relnamespace=(SELECT oid FROM pg_namespace WHERE nspname='public')
AND a.relkind='r'
ORDER BY a.relname");
        }

    }

    /**
     * 获取表的结构
     * @param string $db
     * @param string $table
     * @return \Illuminate\Support\Collection
     */
    public function tablesInfo(string $db, string $table)
    {
        if ($this->_drive == 'mysql') {
            return $this->_db->table("COLUMNS")->where([
                'table_schema' => $db,
                'table_name' => $table
            ])->select([
                "column_name",
                "column_type",
                "column_key",
                "is_nullable",
                "column_default",
                "column_comment"
            ])->get();
        } else {
            return collect($this->_db->select("SELECT
	A.attname AS column_name,
	format_type ( A.atttypid, A.atttypmod ) AS column_type,
	(case when (select count(*) from pg_constraint where conrelid = a.attrelid and conkey[1]=attnum and contype='p')>0 then 'PRI' else '' end) as column_key,
	(case when a.attnotnull=true then 'Y' else 'N' end) as is_nullable,
	d.adsrc AS COLUMN_default,
	(case when col_description(a.attrelid,a.attnum) is null then '' else col_description(a.attrelid,a.attnum) end) as column_comment 
FROM
	pg_class C,
	pg_attribute A
	LEFT JOIN (
SELECT
	A.attname,
	ad.adsrc 
FROM
	pg_class C,
	pg_attribute A,
	pg_attrdef ad 
WHERE
	relname ='{$table}' 
	AND ad.adrelid = C.oid 
	AND adnum = A.attnum 
	AND attrelid = C.oid 
	) AS d ON A.attname = d.attname 
WHERE
	C.relname = '{$table}' 
	AND A.attrelid = C.oid 
	AND A.attnum > 0"));
        }

    }

    /**
     * 执行sql语句
     * @param string $sql
     * @return array|bool
     * @throws \Exception
     */
    public function exec(string $sql)
    {
        try {
            if (stripos(substr($sql, 0, 10), 'select') !== false) {
                return json_decode(json_encode($this->_db->select($sql)),true);
            }
            return $this->_db->statement($sql);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}