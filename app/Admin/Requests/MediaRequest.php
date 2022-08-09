<?php

namespace App\Admin\Requests;

class MediaRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'           => 'string',
            'description'     => 'string',
            'cover_image_id'  => 'int',
            'media_id'        => 'int',
            'play_list_id'    => 'int',
        ];
    }

    public  function attributes()
    {
        return [
            'title'           => '标题',
            'description'     => '描述',
            'cover_image_id'  => '封面图',
            'media_id'        => '资源',
            'play_list_id'    => '关联播放列表',
        ];
    }
}
