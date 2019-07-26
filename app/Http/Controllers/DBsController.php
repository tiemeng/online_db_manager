<?php
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/7/22
 * Time: 13:31
 */

namespace App\Http\Controllers;


use App\Models\DBs;
use Illuminate\Http\Request;

class DBsController extends BaseController
{
    public function index(Request $request)
    {
        $search = [
            'schema_name' => $request->schema_name
        ];
        $dbs = DBs::getDBs($search);

        return view('dbs.index', ['dbs' => $dbs,'search'=>$search]);
    }

    public function tables(Request $request){
        $db = $request->db;
        $tables = DBs::getTables($db);
        $tablesInfo = [];
        foreach ($tables as $table){
            $tablesInfo[$table->table_name] = DBs::tablesInfo($db,$table->table_name);
        }
        return view("dbs.tables",['db'=>$db,'tables'=>$tables,"tablesInfo"=>$tablesInfo]);
    }

    public function getDbs(Request $request){
        $type = $request->type;

    }

}