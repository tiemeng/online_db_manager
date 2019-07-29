<?php
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/7/22
 * Time: 13:31
 */

namespace App\Http\Controllers;


use App\Models\DbConnection;
use App\Models\DbInfo;
use Illuminate\Http\Request;

class DBsController extends BaseController
{
    /**
     * 数据库列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = [];
        $conn_name = $request->conn_name;
        $schema_name = $request->schema_name;
        $conn = DbConnection::getList([]);
        if (!$conn_name) {
            $conn_name = $conn[0]->conn_name;
        }
        if ($schema_name) {
            $search = ['schema_name' => $schema_name];
        }

        $dbinfoModel = new DbInfo($conn_name);
        $dbs = $dbinfoModel->getDbsByConnection($search);

        return view('dbs.index', ['dbs' => $dbs, 'search' => $search, 'conns' => $conn, 'conn_name' => $conn_name]);
    }

    /**
     * 获取所有的表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tables(Request $request)
    {
        $db = $request->db;
        $conn_name = $request->conn_name;
        $dbInfoModel = new DbInfo($conn_name);
        $tables = $dbInfoModel->getTables($db);
        $tablesInfo = [];
        foreach ($tables as $table) {
            $tablesInfo[$table->table_name] = $dbInfoModel->tablesInfo($db, $table->table_name);
        }
        return view("dbs.tables", ['db' => $db, 'tables' => $tables, "tablesInfo" => $tablesInfo]);
    }


}