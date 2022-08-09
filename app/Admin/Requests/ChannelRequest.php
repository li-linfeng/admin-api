<?php

namespace App\Admin\Requests;

class ChannelRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                 => 'required|string',
            // 'background_image_id'  => 'required|int',
            // 'icon_choose_id'       => 'required|int',
            // 'icon_unchoose_id'     => 'required|int',
            'description'          => 'required|string',
            'default_play_list_id' => 'int',
            'special_play_list_id' => 'int',
            'play_list_id'         => 'sometimes|string',
        ];
    }

    public  function attributes()
    {
        return [
            'name'                 => '标题',
            'background_image_id'  => '背景图',
            'icon_choose_id'       => '选中状态图标',
            'icon_unchoose_id'     => '未选中状态图标',
            'description'          => '描述',
            'default_play_list_id' => '默认播放列表id',
            'special_play_list_id' => '特殊播放列表id',
            'play_list_id'         => '关联播放列表',
        ];
    }
}
