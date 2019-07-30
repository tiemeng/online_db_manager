<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\ApplyRequest;
use App\Models\DataChange;
use App\Models\DbConnection;
use App\Models\DbInfo;
use App\Util\Common;
use App\Util\ResQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 数据变更申请
 * @package App\Http\Controllers
 */
class DataChangeController extends BaseController
{

    private $_db_type = ['MYSQL', 'PGSQL'];
//    private $_db_type = ['MYSQL'];
    private $_status = [1 => '待审核', '审核通过', '驳回', '已执行'];

    /**
     * 申请列表
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
     * 添加页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return $this->view(null, ['dbType' => $this->_db_type]);
    }

    /**
     * 添加数据
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
     * 编辑页面
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
     * 更新数据
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
     * 申请审核
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
            $msg = $status == 2 ? "你的sql申请已审核通过" : "你的sql申请被驳回";
            Common::applyNotice($admin->email,$admin->name,$msg);
            return $this->reJson(200,'审核成功');
        } catch (\Exception $e) {
            return $this->reJson(102,'审核失败');
        }
    }

    /**
     * 通过驱动获取所有的数据库
     * @param string $driver
     * @return array
     */
    public function getDbs(string $driver){
        $list = DbConnection::getDBByDriver($driver);
        return $this->reJson(200,'success',$list);
    }

    /**
     * 获取所有的表明
     * @param string $conn
     * @param string $db
     * @return array
     */
    public function getTables(string $conn,string $db){
        $dbInfo = new DbInfo($conn,$db);
        return $this->reJson(200,'success',$dbInfo->getTablesByDb($db));

    }

    /**
     * 执行sql
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function exec(Request $request){
        $id = $request->id;
        $info = DataChange::getInfoById($id);
        if(empty($info)){
            return $this->reJson(101,'数据不存在');
        }
        $conn_name = $info->conn_name;
        $dbInfoModel = new DbInfo($conn_name,$info->db_name);
        $admin = Auth::guard('admin')->user();
        try{
            $sql = $info->exc_sql;
            $res = $dbInfoModel->exec($sql);
            if($res){
                DataChange::updateData(['status'=>4],['id'=>$id]);
                Common::applyNotice($admin->email,$admin->name,"你申请的sql已经处理完成");
                return $this->reJson(200,'执行成功');
            }

            return $this->reJson(101,'执行失败');
        }catch (\Exception $e){
            DataChange::updateData(['status'=>1,'fail_reason'=>$e->getMessage()],['id'=>$id]);
            Common::applyNotice($admin->email,$admin->name,"你申请的sql处理失败，请重新编辑。失败原因：".$e->getMessage());
            return $this->reJson(101,$e->getMessage());
        }

    }

}
