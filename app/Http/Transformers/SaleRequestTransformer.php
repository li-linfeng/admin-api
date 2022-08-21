<?php

namespace App\Http\Transformers;

use App\Models\SaleRequest;

class SaleRequestTransformer extends BaseTransformer
{

    protected $availableIncludes = [];


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
            'upload_id'                    => $saleRequest->upload_id,
            'remark'                       => $saleRequest->remark,
            'upload_url'                   => optional($saleRequest->upload)->url,
            'upload_filename'              => optional($saleRequest->upload)->filename,
            'status'                       => $saleRequest->status,
            'status_cn'                    => $saleRequest->status_cn,
            'created_at'                   => $saleRequest->created_at->toDateTimeString(),
        ];
    }
}
