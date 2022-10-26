<?php

namespace App\Http\Requests;


class SaleRqRequest extends FormRequest
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
        return [
            'project_no'    => 'required',
            'product_type'  => 'required',
            'customer_type' => 'required',
            // 'device_name'   => 'required',
            // 'driver_type'   => 'required',
            // 'driver_power'  => 'required',
            // 'rpm'           => 'required',
            // 'torque'        => 'required',
            // 'shaft_one_diameter_tolerance',
            // 'shaft_two_diameter_tolerance',
            // 'shaft_one_match_distance',
            // 'shaft_two_match_distance',
            // 'upload_ids',
            // 'remark',
        ];
    }

    public function messages()
    {
        return [
            'project_no.required'    => '项目编号不能为空',
            'product_type.required'  => '商品类型不能为空',
            'customer_type.required' => '客户性质不能为空',
            'device_name.required'   => '设备名称不能为空',
            'driver_type.required'   => '驱动类型不能为空',
            'driver_power.required'  => '驱动功率不能为空',
            'rpm.required'           => '转速不能为空',
            'torque.required'        => '力矩不能为空',
        ];
    }
}
