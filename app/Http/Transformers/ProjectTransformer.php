<?php

namespace App\Http\Transformers;

use App\Models\Project;

class ProjectTransformer extends BaseTransformer
{

    protected $availableIncludes = ['user'];

    public function transform(Project $project)
    {
        return [
            'id'          => $project->id,
            'name'        => $project->name,
            'description' => $project->description,
            'status'      => $project->status,
            'status_cn'   => $project->status_cn,
            'created_at'  => $project->created_at->toDateTimeString(),
        ];
    }


    public function includeUser(Project $project)
    {
        if (!$project->user) {
            return $this->nullObject();
        }
        return $this->item($project->user, new UserTransformer());
    }


    protected function setChild()
    {
        $this->is_child = true;
    }
}
