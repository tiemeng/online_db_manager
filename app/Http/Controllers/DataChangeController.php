<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\ApplyRequest;
use App\Models\DataChange;
use App\Models\DbConnection;
use App\Models\DbInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DataChangeController extends BaseController
{

    private $_db_type = ['MYSQL', 'POSTGRESQL', "ORACLE"];
    private $_status = [1 => '待审核', '审核通过', '驳回', '已执行'];

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = [];
        if ($request->db_name) {
            $search['db_name'] = $request->db_name;
        }
        if ($request->table_name) {
            $search['table_name'] = $request->table_name;
        }
        $list = DataChange::getList($search);
        return $this->view(null,
            ['dbType' => $this->_db_type, 'search' => $search, 'list' => $list, 'status' => $this->_status]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return $this->view(null, ['dbType' => $this->_db_type]);
    }

    /**
     * @param ApplyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ApplyRequest $request)
    {
        try {
            $admin = Auth::guard('admin')->user();
            $insertData = $request->all();
            $insertData['apply_uid'] = $admin->id;
            unset($insertData['_token']);
            DataChange::insertData($insertData);
            flash('添加成功')->success()->important();

            return redirect()->route('datachange.index');
        } catch (\Exception $e) {
            flash("添加失败")->error()->important();
            return redirect()->route('datachange.index');
        }

    }


    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $info = DataChange::getInfoById($id);


        $dbs = $this->getDbs($info['db_type'])['data'];
        $tables = $this->getTables($info['conn_name'],$info['db_name'])['data'];

        return $this->view(null, ['info' => $info, 'dbType' => $this->_db_type,'dbs'=>$dbs,'tables'=>$tables]);
    }

    /**
     * @param ApplyRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ApplyRequest $request, $id)
    {
        try {
            $updateData = [
                'db_type' => $request->db_type,
                'db_name' => $request->db_name,
                'table_name' => $request->table_name,
                'exc_sql' => $request->exc_sql,
            ];
            DataChange::updateData($updateData, ['id' => $id]);
            flash('更新成功')->success()->important();
            return redirect()->route('datachange.index');
        } catch (\Exception $e) {
            flash($e->getMessage())->error()->important();
        }
        return redirect()->route('datachange.index');


    }


    /**
     * @param Request $request
     * @return false|string
     */
    public function status(Request $request)
    {
        try {
            $id = intval($request->id);
            $status = intval($request->status);
            if(empty($id) || empty($status)){
                return $this->reJson(100,'参数错误');
            }
            $admin = Auth::guard('admin')->user();
            $updateData = ['status' => $status, 'audit_uid' => $admin->id];
            $where = ['id' => $id];
            DataChange::updateData($updateData, $where);
            return $this->reJson(200,'审核成功');
        } catch (\Exception $e) {
            return $this->reJson(102,'审核失败');
        }
    }

    public function getDbs(string $driver){
        $list = DbConnection::getDBByDriver($driver);
        return $this->reJson(200,'success',$list);
    }

    public function getTables(string $conn,string $db){
        $dbInfo = new DbInfo($conn);
        return $this->reJson(200,'success',$dbInfo->getTablesByDb($db));

    }
}
