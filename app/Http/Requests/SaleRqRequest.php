<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'seq_num',
            'product_type',
            'customer_type',
            'device_name',
            'driver_type',
            'driver_power',
            'rpm',
            'torque',
            'shaft_one_diameter_tolerance',
            'shaft_two_diameter_tolerance',
            'shaft_one_match_distance',
            'shaft_two_match_distance',
            'upload_ids',
            'remark',
        ];
    }
}
