<?php

namespace App\Http\Transformers;

use App\Models\SaleRequest;

class SaleRequestTransformer extends BaseTransformer
{

    protected $availableIncludes = ['uploads'];


    public function transform(SaleRequest $saleRequest)
    {
        return [
            'id'                           => $saleRequest->id,
            'sale_num'                     => $saleRequest->sale_num,
            'product_type'                 => $saleRequest->product_type,
            'customer_type'                => $saleRequest->customer_type,
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
            'handle_user_id'               => $saleRequest->handle_user_id,
            'leader_id'                    => $saleRequest->leader_id,
            'can_dispatch'                 => $saleRequest->handle_user_id ? false : true,
            'status_cn'                    => $saleRequest->status_cn,
            'created_at'                   => $saleRequest->created_at->toDateTimeString(),
            'user_id'                      => $saleRequest->user_id,
        ];
    }


    public function includeUploads(SaleRequest $saleRequest)
    {
        if ($saleRequest->uploads->isEmpty()) {
            return $this->null();
        }
        return $this->collection($saleRequest->uploads, new UploadTransformer(), 'flatten');
    }
}
