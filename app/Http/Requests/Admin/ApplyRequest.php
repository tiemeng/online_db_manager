<?php
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/7/22
 * Time: 16:17
 */

namespace App\Http\Requests\Admin;


use Illuminate\Foundation\Http\FormRequest;

class ApplyRequest extends FormRequest
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
            'db_type' => 'required|between:5,20',
            'db_name' => 'required|max:40',
            'table_name' => 'required|max:40',
            'exc_sql' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'db_type.required' => '数据库类型不能为空',
            'db_type.between' => '数据库类型长度应该在5~20位之间',
            'db_name.required' => "数据库名不能为空",
            'db_name.max' => '数据库名最大不能超过40个字符',
            'table_name.required' => "数据表名不能为空",
            'table_name.max' => '数据表名最大不能超过40个字符',
            'exc_sql.required' => 'SQL不能为空'
        ];
    }
}