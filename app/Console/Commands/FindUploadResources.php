<?php

namespace App\Console\Commands;

use App\Models\Resource;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FindUploadResources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resource:findNewResource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'findNewResource';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dir = storage_path() . '/uploads';
        $filenames = $this->getFileNamesByDir($dir);
        $insert = [];
        foreach ($filenames as $filename) {
            if (preg_match("/audios/", $filename)) {
                $type = 'audio';
            }
            if (preg_match("/videos/", $filename)) {
                $type = 'video';
            }
            $data =  $this->createResource($filename, $type);
            $insert[] = [
                'type'        => $type,
                'path'        => $data['filename'],
                'url'         => $data['url'],
                'origin_name' => $data['origin_name'],
                'total_time'  => $data['total_time'],
                'created_at'  => Carbon::now()->toDateTimeString(),
                'updated_at'  => Carbon::now()->toDateTimeString(),
            ];
        }
        Video::insert($insert);
    }

    protected function createResource($filename, $type)
    {
        //移动文件至新目录
        $file_base_name = basename($filename);
        $new_path = storage_path('app/public');
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $dir = $new_path . '/' . $type . '/' .  date('Y-m-d');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $new_filename =  "{$type}/" .  date('Y-m-d') . '/' .  uniqid() . '.' . $ext;
        rename($filename, $new_path . "/" . $new_filename);

        //生成url链接
        $url = Storage::disk('public')->url($new_filename);

        $total_time = getResourceTotalTime($new_filename);
        return [
            'url'         => $url,
            'filename'    => $new_filename,
            'total_time'  => $total_time,
            'origin_name' => $file_base_name,
        ];
    }



    protected function getAllFiles($path, &$files)
    {
        if (is_dir($path)) {
            $dp = dir($path);
            while ($file = $dp->read()) {
                if ($file !== "." && $file !== "..") {
                    $this->getAllFiles($path . "/" . $file, $files);
                }
            }
            $dp->close();
        }
        if (is_file($path)) {
            $files[] =  $path;
        }
    }

    protected function getFileNamesByDir($dir)
    {
        $files =  array();
        $this->getAllFiles($dir, $files);
        return $files;
    }
}
