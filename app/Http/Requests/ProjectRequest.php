<?php

namespace App\Http\Requests;


class ProjectRequest extends FormRequest
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
            "project_no"    => "required",
            "name"          => "required",
            "customer_name" => "required",
            "product_name"  => "required"
        ];
    }
}
