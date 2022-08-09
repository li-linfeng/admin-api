<?php

namespace App\Admin\Requests;

class PlayListRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                => 'required|string',
            'channel_id'          => 'required|int',
        ];
    }

    public  function attributes()
    {
        return [
            'name'                => '名称',
            'channel_id'          => '频道id',
        ];
    }
}
