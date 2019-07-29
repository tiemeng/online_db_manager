<?php
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/7/25
 * Time: 14:16
 */

namespace App\Http\Controllers;


use App\Http\Requests\Admin\DbConn;
use App\Models\DbConnection;
use App\Util\Common;
use Illuminate\Http\Request;

class DbConnectionController extends BaseController
{
    protected $_diverType = ['MySQL'];

//    protected $_diverType = ['MySQL', "PGSQL"];

    public function index(Request $request)
    {
        $driver = $request->driver;
        $where = [];
        if ($driver) {
            $where['driver'] = $driver;
        }
        $list = DbConnection::getList($where);
        $data = [
            'list' => $list,
            'driver' => $driver,
            'driverType' => $this->_diverType
        ];
        return $this->view(null, $data);
    }

    public function create()
    {
        return $this->view(null, ['driver' => $this->_diverType]);
    }

    public function store(DbConn $request)
    {
        try {
            $requestData = $request->all();
            unset($requestData['_token']);
            $requestData['driver'] = strtolower($requestData['driver']);
            !DbConnection::insertData($requestData) && DbConnection::insertData($requestData);
            flash('添加成功')->success()->important();
            Common::updateDatabaseFile();
        } catch (\Exception $e) {
            flash($e->getMessage())->success()->important();
        }

        return redirect()->route('dbconn.index');
    }

    public function edit($id)
    {
        $info = DbConnection::getInfoById($id);

        return $this->view(null, ['info' => $info, 'driver' => $this->_diverType]);
    }

    public function update(DbConn $request, $id)
    {
        try {
            $updateData = [
                'conn_name' => $request->conn_name,
                'username' => $request->username,
                'password' => $request->password,
                'port' => $request->port,
                'driver' => strtolower($request->driver),
                'charset' => $request->charset,
                'host' => $request->host,
                'database' => $request->database,
            ];
            DbConnection::updateData($updateData, ['id' => $id]);
            flash('更新成功')->success()->important();
            Common::updateDatabaseFile();
        } catch (\Exception $e) {
            flash($e->getMessage())->error()->important();
        }
        return redirect()->route('dbconn.index');
    }
}