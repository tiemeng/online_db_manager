<?php

namespace App\Services;

use Auth;
use App\Handlers\ImageUploadHandler;
use App\Repositories\AdminsRepository;
use Illuminate\Support\Facades\Hash;

class AdminsService
{
    protected $uploader;

    protected $adminsRepository;

    protected $actionLogsService;

    /**
     * AdminsService constructor.
     * @param AdminsRepository $adminsRepository
     * @param ImageUploadHandler $imageUploadHandler
     * @param ActionLogsService $actionLogsService
     */
    public function __construct(AdminsRepository $adminsRepository, ImageUploadHandler $imageUploadHandler,ActionLogsService $actionLogsService)
    {
        $this->uploader = $imageUploadHandler;
        $this->adminsRepository = $adminsRepository;
        $this->actionLogsService = $actionLogsService;
    }

    /**
     * 创建管理员数据
     * @param $request
     * @return mixed
     */
    public function create($request)
    {
        $datas = $request->all();

        $datas['password'] = Hash::make($request->password);
        $datas['create_ip'] = $request->ip();
        $datas['last_login_ip'] = $request->ip();

        $admin = $this->adminsRepository->create($datas);

        //插入模型关联数据
        $admin->roles()->attach($request->role_id);

        return $admin;
    }

    /**
     * 更新管理员资料
     * @param $request
     * @param $id
     * @return mixed
     */
    public function update($request, $id)
    {
        $datas = $request->all();

        $admin = $this->adminsRepository->ById($id);

        if (isset($datas['password'])) {
            $datas['password'] = Hash::make($request->password);
        } else {
            unset($datas['password']);
        }

        $admin->update($datas);

        //更新关联表数据
        $admin->roles()->sync($request->role_id);

        return $admin;
    }

    /**
     * 获取管理员的详细资料
     * @param $id
     * @return mixed
     */
    public function ById($id)
    {
        return $this->adminsRepository->ById($id);
    }

    /**
     * 获取管理员列表 with ('roles')
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAdminsWithRoles()
    {
        return $this->adminsRepository->getAdminsWithRoles();
    }


    /**
     * 登录管理员
     * @param $request
     * @return bool
     */
    public function login($request)
    {
        if(!Auth::guard('admin')->attempt([
            'name'     => $request->name,
            'password' => $request->password,
            'status'   => 1,
        ])){
            //记录登录操作记录
            $this->actionLogsService->loginActionLogCreate($request,false);
            return false;
        }


        //记录登录操作记录
        $this->actionLogsService->loginActionLogCreate($request,true);

        return true;
    }

    /**
     * 退出登录
     * @return mixed
     */
    public function logout()
    {
        return Auth::guard('admin')->logout();
    }
}