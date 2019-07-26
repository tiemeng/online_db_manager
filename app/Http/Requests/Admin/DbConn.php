<?php
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/7/25
 * Time: 15:00
 */

namespace App\Http\Requests\Admin;


use Illuminate\Foundation\Http\FormRequest;

class DbConn extends FormRequest
{
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
        return [
            'driver' => 'required',
            'conn_name' => 'required|max:20',
            'port' => 'required|integer',
            'database' => 'required',
            'username' => 'required|max:20',
        ];
    }

    public function messages()
    {
        return [
            'driver.required' => '数据库驱动不能为空',
            'conn_name.required' => "连接名不能为空",
            'conn_name.max' => '连接名最大不能超过20个字符',
            'port.required' => "端口号不能为空",
            'port.integer' => "必须为整型",
            'username.max' => '用户名最大不能超过20个字符',
            'database.required' => '数据库名不能为空',
            'username.required' => '用户名不能为空'
        ];
    }
}