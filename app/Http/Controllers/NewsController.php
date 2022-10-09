<?php

namespace App\Http\Controllers;

use App\Http\Transformers\NewsTransformer;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    //

    public function index(Request $request, NewsTransformer $newsTransformer)
    {
        $news = News::filter($request->all())->orderByDesc('created_at')->paginate($request->input('per_page',10));
        return $this->response()->paginator($news, $newsTransformer);
    }


    public function show(News $news ,NewsTransformer $newsTransformer)
    {
        return $this->response()->item($news, $newsTransformer);
    
    }

    public function store(Request $request,NewsTransformer $newsTransformer)
    {
        $news = News::create($request->only(['title', 'content', 'status','md_content']));
        return $this->response()->item($news, $newsTransformer);
    
    }

    public function update(News $news , Request $request)
    {
        $news->update($request->only(['title', 'content', 'status','md_content']));
        return $this->response()->noContent();
    }
    
    public function delete(News $news)
    {
        $news->delete();
        return $this->response()->noContent();
    }
}
