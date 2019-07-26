<?php

namespace App\Models\Traits;

use App\Handlers\Tree;
use App\Repositories\RulesRepository;

trait RbacCheck
{

    /**
     * 获取当前用户的所有权限
     * @return mixed
     */
    public function getRules()
    {
        if(!session()->has('rules'))
        {
            $permissions = [];

            foreach ($this->roles as $role) {
                $permissions = array_merge($permissions, $role->rules()->pluck('route')->toArray());
            }

            /**获得当前用户所有权限路由*/
            $permissions = array_unique($permissions);

            session(['rules'=>$permissions]);
        }

        return session('rules');
    }

    /**
     * 获取树形菜单导航栏
     * @return array
     */
    public function getMenus()
    {

        if (!session()->has('menus'))
        {
            $rules = [];
            //判断是否是超级管理员用户组
            if (in_array(1, $this->roles->pluck('id')->toArray()))
            {
                //超级管理员用户组获取全部权限数据
                $rules = (new RulesRepository())->getRulesAndPublic()->toArray();

            } else {

                foreach ($this->roles as $role)
                {
                    $rules = array_merge($rules, $role->rulesPublic()->toArray());
                }

                if($rules)
                {
                    $rules = unique_arr($rules);
                }
            }
            session(['menus'=>$rules]);
        }


        $rules = session('menus');

        return Tree::array_tree($rules);
    }

    /**
     * 删除权限缓存和菜单缓存
     * @return bool
     */
    public function clearRuleAndMenu()
    {
        return session()->flush();
    }
}