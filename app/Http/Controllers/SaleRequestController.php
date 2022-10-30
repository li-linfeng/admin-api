<?php

namespace App\Http\Controllers;

use App\Exports\SaleRequestExport;
use App\Http\Requests\SaleRqRequest;
use App\Http\Transformers\SaleRequestTransformer;
use App\Models\PreSaleRequest;
use App\Models\Project;
use App\Models\SaleRequest;
use App\Models\Todo;
use App\Models\Upload;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SaleRequestController extends Controller
{

    public function list(Request $request, SaleRequestTransformer $transformer)
    {
        $filter = $request->only('filter_status');
        $filter['filter_keyword'] = $request->only('filter_col', 'filter_val');
        $filter['filter_display'] = 1;
        $data = SaleRequest::filter($filter)
            ->with(['uploads', 'user', 'handler'])
            ->paginate($request->input('per_page', 10));

        return $this->response()->paginator($data, $transformer, [], function ($resource, $fractal) {
            $fractal->parseIncludes(['uploads', 'user', 'handler']);
        });
    }


    public function store(SaleRqRequest $request)
    {
        $data = $request->all();
        $project = Project::where('project_no', $request->project_no)->first();
        $data['user_id'] = auth('api')->id() ?: 0;
        $types = implode(",", $request->product_type);
        $data['handle_type'] = $types ?$request->product_type[0]: "";
        $data['product_type'] = $types ;
        $data['customer_name'] = $project ? $project->customer_name :"";

        $sale =  SaleRequest::create($data);
        $ids = explode(",", $request->upload_ids);
        Upload::whereIn('id',  $ids)->update(['source_id' => $sale->id]);
        return $this->response()->noContent();
    }

    public function update(SaleRequest $request, SaleRqRequest $saleRqRequest)
    {
        $this->canHandle($request);
        $params = $saleRqRequest->all();
        $params['status'] = 'open';
        $types = implode(",", $request->product_type);
        $data['handle_type'] = $types ?$request->product_type[0]: "";
        $data['product_type'] = $types ;

        $request->update( $params);

        Upload::where('source_type', 'sale_request')->where('source_id', $saleRqRequest->id)->update(['source_id' => 0]);

        $files = explode(",", $saleRqRequest->upload_ids);
        if (count($files)) {
            Upload::whereIn('id', $files)->update(['source_id' => $saleRqRequest->id, 'source_type' => 'sale_request']);
        }
        $ids =   PreSaleRequest::where('sale_id', $request->id)->pluck('id')->toArray();

        if($ids){ //删除 售前处理关联上传
            Upload::where('source_type', 'pre_sale')->whereIn('source_id',$ids)->delete();
        }
        //删除 售前处理
        PreSaleRequest::where('sale_id', $request->id)->delete();

        
        return $this->response()->noContent();
    }

    public function delete(SaleRequest $request)
    {
        $this->canHandle($request);
        $ids =   PreSaleRequest::where('sale_id', $request->id)->pluck('id')->toArray();
        if($ids){ //删除 售前处理关联上传
            Upload::where('source_type', 'pre_sale')->whereIn('source_id',$ids)->delete();
        }
        //删除 售前处理
        PreSaleRequest::where('sale_id', $request->id)->delete();

        $request->delete();
        
        return $this->response()->noContent();
    }

    public function publish(SaleRequest $request)
    {
        $this->canHandle($request);
        $request->status = 'published';
        $request->save();
        //插入一条退回代办
        if($request->handler){
            Todo::create([
                'content'   => "项目编号为{$request->project_no}的需求待处理",
                'type'      => 'pre_sale',
                'user_id'   => $request->handler->id,
                'source_id' => $request->project_no
            ]);
        }
        return $this->response()->noContent();
    }

    public function finish(SaleRequest $request)
    {
    
        $request->update(['status' => 'finish']);

        if($request->handler){
            Todo::create([
                'content'   => "项目编号为{$request->project_no}的售前处理已完成",
                'type'      => 'pre_sale',
                'user_id'   => $request->handler->id,
                'source_id' => $request->project_no
            ]);
        }
        return $this->response()->noContent();
    }

    public function return(SaleRequest $request)
    {
        $this->canHandle($request);
        $request->status = 'return';
        $request->return_reason = app('request')->input('return_reason');
        $request->save();

        //删除关联的pre_sale记录
        $preSaleIds = PreSaleRequest::where('sale_id', $request->id)->pluck('id')->toArray();
        if($preSaleIds){ //将之前的上传的文件资源id设为0
            Upload:: whereIn('source_id', $preSaleIds)->where('source_type', 'pre_sale')->update(['source_id'=>0]);
        }
        PreSaleRequest::where('sale_id', $request->id)->delete();
        //插入一条退回代办
        $content ="工程编号为{$request->project_no}的需求被退回，请修改后重新发布";
        Todo::create([
            'content'   => $content,
            'type'      => 'sale_request',
            'user_id'   => $request->user_id,
            'source_id' => $request->project_no
        ]); 

        return $this->response()->noContent();
    }


    

    protected function canHandle(SaleRequest $saleRequest)
    {
        if ($saleRequest->user_id == request()->user_info['user_id'] ||  request()->user_info['is_super']){
            return true;
        }
        abort(403, '没有权限进行此操作');
    }

    
    public function download(Request $request)
    {
        $filter = $request->only('filter_status');
        $filter['filter_keyword'] = $request->only('filter_col', 'filter_val');
        $saleRequests = SaleRequest::filter($filter)
        ->with([ 'user', 'handler'])
        ->get();
        return Excel::download(new SaleRequestExport($saleRequests), 'sale_request.xlsx');
    }
}
