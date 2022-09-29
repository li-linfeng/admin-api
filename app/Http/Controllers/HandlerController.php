<?php

namespace App\Http\Controllers;

use App\Http\Transformers\HandlerTransformer;
use App\Models\Handler;
use Illuminate\Http\Request;

class HandlerController extends Controller
{
    
    public function index(Request $request, HandlerTransformer $transformer)
    {
       $handlers = Handler::filter($request->all())->with(['user'])->paginate($request->input('per_page', 10));
        return $this->response()->paginator($handlers, $transformer, [], function($resource,$fractal){
            $fractal->parseIncludes(['user']);
        }); 
    }


    public function setHandler(Handler $handler, Request $request)
    {
        $handler->handler_id = $request->handler_id;
        $handler->save();
        return $this->response()->noContent();
    }
}
