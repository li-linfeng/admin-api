<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRqRequest;
use App\Http\Transformers\SaleRequestTransformer;
use App\Models\SaleRequest;
use Illuminate\Http\Request;

class SaleRequestController extends Controller
{
    public function getUniqueId()
    {
        return $this->response()->array([
            'uuid' => date("Ymd") . uniqid()
        ]);
    }


    public function list(Request $request, SaleRequestTransformer $transformer)
    {
        $keyword = [
            $request->filter_col,
            $request->filter_val,
        ];
        $data = SaleRequest::filter(['filter_keyword' => $keyword])
            ->where('user_id', auth('api')->id())
            ->paginate($request->input('per_page', 10));

        return $this->response()->paginator($data, $transformer);
    }


    public function store(SaleRqRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = auth('api')->id() ?: 0;
        SaleRequest::create($data);
        return $this->response()->noContent();
    }

    public function update(SaleRqRequest $request, SaleRequest $saleRequest)
    {
        $data = $request->all();
        $data['user_id'] = auth('api')->id() ?: 0;
        $saleRequest->update($request->all());
        return $this->response()->noContent();
    }

    public function delete(SaleRequest $request)
    {
        $request->delete();
        return $this->response()->noContent();
    }
}
