<?php

use App\Models\Category;
use App\Models\Material;
use App\Models\PlayList;
use App\Models\Resource;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('init', function () {
    $prefix = 'HN';
    $types = ['C','G','S'];
    $series_codes = ['TL-Torque Limiter','GD-Grid','GR-Gear','DC-Disc','JW-Jaw','FE-Flexible','HB-Wrapflex','TE-Tyre','WD-Wind','CE-Composite','RB-RUBBER BUFFER'];

    $data = [];
    foreach($types as $type){
        foreach($series_codes as $code){
            $item = explode("-", $code);
            $data[] = [
                'name'        => $prefix.$type.$item[0],
                'name_cn'     => $prefix.$type.$item[0],
                'description' => $item[1],
                'type'        => $type,
                'code'        => $item[0],
                'created_at'  => Carbon::now()->toDateTimeString(),
                'updated_at'  => Carbon::now()->toDateTimeString()
            ];
        }
    }
    $data[]= [
        'name'        => 'HNXXX',
        'name_cn'     => 'HNXXX',
        'description' => '公用零件',
        'type'        => 'XX',
        'code'        => 'XX',
        'created_at'  => Carbon::now()->toDateTimeString(),
        'updated_at'  => Carbon::now()->toDateTimeString()
    ];
    Category::insert($data);
});


Artisan::command('test', function () {

    DB::beginTransaction();

    $number = Material::select(DB::raw('max(seq)+1 as number'))->lockForUpdate()->value('number');
    
    DB::commit();
    dd(str_pad($number,4,'0',STR_PAD_LEFT));
});