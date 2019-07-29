<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if(request()->method() == 'POST')
        {
            return [
                'name'     => 'required|between:3,10',
                'password' => 'required',
                'role_id'  => 'required:integer',
                'status'   => 'required:integer',
                'email'   => 'required|email',
            ];
        }else{
            return [
                'name'     => 'required|between:3,10',
                'role_id'  => 'required:integer',
                'status'   => 'required:integer',
                'email'   => 'required|email',
            ];
        }
    }

    /**
     * 提示信息
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'     => '用户名不能为空',
            'name.between'      => '用户名长度应该在3~10位之间',
            'password.required' => '密码不能为空',
            'role_id.required'  => '用户所属角色不能为空',
            'role_id.integer'   => '表单不合法',
            'status.required'   => '状态不能为空',
            'status.integer'    => '表单不合法',
            'email.required'    => '邮箱不能为空',
            'email.email'    => '邮箱不正确',
        ];
    }
}
