<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018.12.20
 * Time: 17:33
 */

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class PlatformsRequest  extends FormRequest
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
        switch($this->method()) {

            case 'PUT':
            {
                return [
                    'company_name' => 'required',
                    'status' => 'required|integer',
                    'notify_url' => 'required',
                    'name' => 'required'
                ];
            }
            case 'POST':
            {
                return [
                    'company_name' => 'required',
                    'status' => 'required|integer',
                    'notify_url' => 'required',
                    'name' => 'required'
                ];
            }
            default:
                return [];
        }

    }


    public function messages()
    {
        return [
            'company_name.required'  => '公司名称不能为空',
            'status.required'    => '状态不能为空',
            'status.integer'     => '状态不合法',
            'notify_url.required'  => 'notify_url 不能为空',
            'name.required' => '平台名称不能为空',
        ];
    }
}