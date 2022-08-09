<?php

namespace App\Http\Requests;

class AuthLoginRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *         
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required|string',
            'social' => 'required|in:apple,weixin'
        ];
    }
}
