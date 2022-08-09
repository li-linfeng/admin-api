<?php

namespace App\Imports;

use App\Models\Channel;
use App\Models\PlayList;
use App\Models\Resource;
use App\Models\Video;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class ResourceImport implements ToCollection, WithHeadingRow, WithEvents
{
    use Importable, RegistersEventListeners;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $videos = [
                'total_time'  => (int)$row['total_time'],
                'type'        => $row['type'] == '音频' ? "audio" : 'video',
                'url'         => $row['url'],
                'origin_name' => $row['origin_name'],
            ];

            $video = Video::create($videos);
            $channel = Channel::where('name', $row['channel_name'])->first();
            $play_list = PlayList::firstOrCreate(['name' => $row['play_list'], 'channel_id' => $channel->id]);
            $resource = [
                'description'  => $row['description'],
                'play_list_id' => $play_list->id,
                'title'        => $row['title'],
                'total_time'   => (int)$row['total_time'],
                'media_id'     => $video->id,
            ];
            Resource::create($resource);
        }
    }
}
