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
        ];
    }

    public function messages()
    {
        return [
            'project_no.required'    => '项目编号不能为空',
            'product_type.required'  => '商品类型不能为空'
        ];
    }
}
