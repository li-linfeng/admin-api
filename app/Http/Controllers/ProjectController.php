<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Transformers\ProjectTransformer;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function list(Request $request, ProjectTransformer $projectTransformer)
    {
        $projects = Project::with(['user'])->filter($request->all())->OrderByDesc("created_at")->paginate($request->per_page);
        return $this->response->paginator($projects, $projectTransformer, [], function ($resource, $fractal) {
            $fractal->parseIncludes(['user']);
        });
    }


    public  function store(ProjectRequest $request, ProjectTransformer $projectTransformer)
    {

        $user_id = auth('api')->id() ?: 0;
        $params = array_merge($request->all(), ['user_id' => $user_id]);
        $project = Project::create($params);
        return $this->response()->item($project, $projectTransformer);
    }

    public  function status(Project $project, Request $request)
    {
        $project->status = $request->status;
        $project->save();
        return $this->response()->noContent();
    }


    public  function delete(Project $project)
    {
        $project->delete();
        return $this->response()->noContent();
    }
}
