<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Material;
use App\Models\MaterialRel;
use App\Models\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
 
    public function tree(Request $request )
    {
        $category = Category::find($request->filter_category_id);
        $data = [
            'sub-assembly' =>
                [
                    "label"=>'子装配体',
                    "children"=> [],
                    "id"=> 0,
                ],
            'component' =>
                [
                    "label"=>'零件',
                    "children"=> [],
                    "id"=> 0,
                ],
            'single-component' =>
                [
                    "label"=>'公用零件',
                    "children"=> [],
                    "id"=> 0,
                ],
        ];

      Material::where(function($q) use($request){
        $q->where('type', '=','single-component')
          ->orWhere(function($query) use($request){
                $query->when( $request->type == 'sub-assembly',
                        function($sq){
                            $sq->where('type', 'component');
                       },function($sq){
                        $sq->whereIn('type', ['component', 'sub-assembly']);
                       })
                      ->Where('category_id',$request->filter_category_id);
            });
      })
        ->where('id','!=', $request->material_id)
        ->get()
        ->groupBy('type')->map(function($items, $key)use (&$data){
            foreach ($items as $material){
                $data[$key]['children'][] = [
                    'id'           => $material->id,
                    'label'        => $material->description,
                    'description'  => $material->description,
                    'has_children' => $material->has_children,
                ];
            }
        });

        if ($request->type == 'sub-assembly'){
            unset($data['sub-assembly']);
        }
        return $this->response()->array(['tree'=>array_values($data),'category'=> $category]);
    }


    public function bindRel(Material $material, Request $request)
    {   
        //创建关联
        if (!$material->has_child){
            abort(422, "此物料没有子分类，请勿添加");
        }

        MaterialRel::where(  'parent_id', $material->id)->delete();
        $insert = [];
        foreach ($request->children as  $child){
          $insert[] = [
                'parent_id' => $material->id,
                'child_id'  => $child['id'],
                'amount'    => $child['amount'],
                'created_at'=> Carbon::now()->toDateTimeString(),
                'updated_at'=> Carbon::now()->toDateTimeString(),
            ];
        }
        MaterialRel::insert($insert);
        $material->update(['status' => 1]);
        return $this->response()->noContent();
    }

    public function store(Request $request)
    {
        $params = $request->only(['has_child','description','category_id','type','label', 'property']);
        if ($request->has_child ==0){
            $params['status'] = 1;
        }
       $material =  Material::create($params);
        //更新文件
        $file_ids = explode(",", $request->file_ids);

        if ($file_ids){
            Upload::whereIn('id', $file_ids)->update(['source_id' => $material->id, 'source_type'=> $material->type]);
        }
        return $this->response()->noContent();
    }

}
