<?php

namespace App\Http\Controllers;

use App\Exports\ProjectExport;
use App\Http\Requests\ProjectRequest;
use App\Http\Transformers\ProjectTransformer;
use App\Models\Project;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProjectController extends Controller
{
    public function list(Request $request, ProjectTransformer $projectTransformer)
    {
        $filter = $request->only('filter_status');
        $filter['filter_keyword'] = $request->only('filter_col', 'filter_val');
        $projects = Project::with(['user'])->filter($filter)->OrderByDesc("created_at")->paginate($request->per_page);
        return $this->response->paginator($projects, $projectTransformer, [], function ($resource, $fractal) {
            $fractal->parseIncludes(['user']);
        });
    }


    public  function store(ProjectRequest $request, ProjectTransformer $projectTransformer)
    {
        $user_id = auth('api')->id();
        $params = array_merge($request->all(), ['user_id' => $user_id]);
        $project = Project::create($params);
        return $this->response()->item($project, $projectTransformer);
    }

    public  function status(Project $project, Request $request)
    {
        $this->canHandle($project);

        $project->status = $request->status;
        $project->close_reason = $request->close_reason;
        $project->save();
        return $this->response()->noContent();
    }


    public  function delete(Project $project)
    {
        $this->canHandle($project);
        $project->delete();
        return $this->response()->noContent();
    }

    protected function canHandle(Project $project)
    {
        if ($project->user_id == request()->user_info['user_id'] ||request()->user_info['is_super']){
            return true;
        }
        abort(403, '没有权限进行此操作');
    }

    public function download(Request $request)
    {
        $filter = $request->only('filter_status');
        $filter['filter_keyword'] = $request->only('filter_col', 'filter_val');
        $projects = Project::with(['user'])->filter($filter)->OrderByDesc("created_at")->get();

        return Excel::download(new ProjectExport($projects), 'project.xlsx');
    }
}
