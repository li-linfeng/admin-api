<?php

namespace App\Http\Transformers;

use App\Models\Project;

class ProjectTransformer extends BaseTransformer
{

    protected $availableIncludes = ['user'];

    public function transform(Project $project)
    {
        return [
            'id'               => $project->id,
            'name'             => $project->name,
            'project_no'       => $project->project_no,
            'customer_name'    => $project->customer_name,
            'product_name'     => $project->product_name,
            'project_time'     => $project->project_time,
            'project_duration' => $project->project_duration,
            'cost'             => formatMoney($project->cost),
            'status'           => $project->status,
            'status_cn'        => $project->status_cn,
            'created_at'       => $project->created_at->toDateTimeString(),
            'close_reason'     => $project->close_reason,
            'compare_info'     => $project->compare_info
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
