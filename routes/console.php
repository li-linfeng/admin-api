<?php

use App\Models\Category;
use App\Models\Handler;
use App\Models\Material;
use App\Models\PlayList;
use App\Models\Resource;
use App\Models\Role;
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
   $types = ['HNCTL','HNCGD','HNCFE','HNCGR','HNCWD'];

    $data = [];
    foreach($types as $type){
        $data[] = [
            'name'        => $type,
            'name_cn'     => $type,
            'description' => $type,
            'type'        => $type,
            'code'        => $type,
            'created_at'  => Carbon::now()->toDateTimeString(),
            'updated_at'  => Carbon::now()->toDateTimeString()
        ];
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

Artisan::command('init-role', function () {

  $roles = ['工程人员', '销售人员'];
  $data = [];
  foreach($roles as $role){
    $data[]= [
        'name' => $role,
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
    ];
  }
  Role::insert($data);
});



Artisan::command('init-handler', function () {

    $modules = [
        'api.sale_requests' => '销售需求',
        'api.preSales'     => '售前处理',
        'api.order'        => '订单',
    ];
    $data = [];
    $types = Category::where('type', '!=', 'XX')->pluck('name')->toArray();
    foreach($modules as $k=> $cn){
        foreach($types as $type){
            $data[]= [
                'module' => $k,
                'module_cn' =>$cn,
                'handler_id' =>0,
                'product_type' =>$type,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ];
        }   
    }
    Handler::insert($data);
  });



Artisan::command('test', function () {

    DB::beginTransaction();

    $number = Material::select(DB::raw('max(seq)+1 as number'))->lockForUpdate()->value('number');
    
    DB::commit();
    dd(str_pad($number,4,'0',STR_PAD_LEFT));
});