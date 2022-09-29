<?php

namespace App\Http\Controllers;

use App\Exports\MaterialExport;
use App\Models\Material;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class OrderItemController extends Controller
{
    public function finish(OrderItem $orderItem)
    {
        $this->canHandle($orderItem);
        if(!$orderItem->material_number){
            abort(422, '请先绑定物料号');
        }
        $orderItem->status = 'finish';
        $orderItem->save();
        //判断订单所有item是否都已经完成

        $total = OrderItem::where('order_id', $orderItem->order_id)->count();
        $finished = OrderItem::where('order_id', $orderItem->order_id)->where('status', 'finish')->count();
        
        if ($total == $finished){
            Order::find($orderItem->order_id)->update(['status' => 'finish']);
        }

        $this->response()->noContent();
    }


    
    public function bindMaterial(OrderItem $orderItem , Request $request)
    {
        // $this->canHandle($orderItem);
        $orderItem->update(['material_number' => $request->material_number]);
        return  $this->response()->noContent();
    }


    public function download(OrderItem $orderItem )
    {
        $this->canHandle($orderItem);
        //获取关联的文件
        $materials = Material::where('label', $orderItem->material_number)
        ->with([ 'children.files','children.children.files'])
        ->get();

        $items = flattenTree($materials);

        $product_name = $items[0]['name'];
        $path = 'zips/'.$product_name ;

        //如果已存在，则先删除
        if(Storage::disk('public')->exists($path.'.zip')){
            Storage::disk('public')->delete($path.'.zip');
        }

        //生成excel
        $excel_name = $product_name .'.xlsx';
        Excel::store(new MaterialExport($items), $path."/".$excel_name , 'public');

       //先创建一个zip文件夹，创建一个对应产品的文件夹，将所有资料copy进来，打包压缩，最后删除其他文件
       Storage::disk('public')->makeDirectory($path);

       $files = collect($items)->pluck('files')->flatten(1)->toArray();//所有物料关联的图纸文档

       //初始化zip
       $zip = new \ZipArchive();
       $zipFileName = Storage::disk('public')->path("/zips/".$product_name.'.zip'); //zip文件的文件名
       $zip->open($zipFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        foreach($files as $k=> $file){
            //获取文件后缀
            $ext = pathinfo($file['filename'], PATHINFO_EXTENSION);
            $filename =$file['name'].'-'.$k.'.'.$ext;
            Storage::disk('public')->copy($file['path'],  $path.'/'. $filename);// 将所有物料关联的图纸文档复制到tmp文件夹
            $zip->addFile(Storage::disk('public')->path($path.'/'. $filename), $filename);
        }
        //将excel 添加进来
        $zip->addFile(Storage::disk('public')->path($path."/". $excel_name), $excel_name);
        $zip->close();

        //最后删除 其他文件
        Storage::disk('public')->deleteDirectory($path);
        return Storage::disk('public')->download('zips/'.$product_name.'.zip');
    }


    protected function canHandle(OrderItem $item)
    {
        if ($item->handler->id == request()->user_info['user_id'] ||request()->user_info['is_super']){
            return true;
        }
        abort(403, '没有权限进行此操作');
    }
}
