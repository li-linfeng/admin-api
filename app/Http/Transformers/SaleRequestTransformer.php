<?php

namespace App\Http\Transformers;

use App\Models\SaleRequest;

class SaleRequestTransformer extends BaseTransformer
{

    protected $availableIncludes = ['uploads', 'user', 'handler'];


    public function transform(SaleRequest $saleRequest)
    {
        return [
            'id'                           => $saleRequest->id,
            'product_type'                 => $saleRequest->product_type,
            'product_type_arr'             => explode(",",$saleRequest->product_type),
            'customer_name'                => $saleRequest->customer_name,
            'device_name'                  => $saleRequest->device_name,
            'driver_type'                  => $saleRequest->driver_type,
            'driver_power'                 => $saleRequest->driver_power,
            'rpm'                          => $saleRequest->rpm,
            'torque'                       => $saleRequest->torque,
            'shaft_one_diameter_tolerance' => $saleRequest->shaft_one_diameter_tolerance,
            'shaft_two_diameter_tolerance' => $saleRequest->shaft_two_diameter_tolerance,
            'shaft_one_match_distance'     => $saleRequest->shaft_one_match_distance,
            'shaft_two_match_distance'     => $saleRequest->shaft_two_match_distance,
            'shaft_space_distance'         => $saleRequest->shaft_space_distance,
            'upload_ids'                   => $saleRequest->upload_ids,
            'remark'                       => $saleRequest->remark,
            'status'                       => $saleRequest->status,
            'status_cn'                    => $saleRequest->status_cn,
            'created_at'                   => $saleRequest->created_at->toDateTimeString(),
            'user_id'                      => $saleRequest->user_id,
            'expect_time'                  => $saleRequest->expect_time,
            'project_no'                   => $saleRequest->project_no,
            'return_reason'                => $saleRequest->return_reason,
        ];
    }


    public function includeUploads(SaleRequest $saleRequest)
    {
        if ($saleRequest->uploads->isEmpty()) {
            return $this->null();
        }
        return $this->collection($saleRequest->uploads, new UploadTransformer(), 'flatten');
    }


    public function includeUser(SaleRequest $saleRequest)
    {
        if (!$saleRequest->user) {
            return $this->nullObject();
        }
        return $this->item($saleRequest->user, new UserTransformer());
    }

    public function includeHandler(SaleRequest $saleRequest)
    {
        if (!$saleRequest->handler) {
            return $this->nullObject();
        }
        return $this->item($saleRequest->handler, new UserTransformer());
    }
}
