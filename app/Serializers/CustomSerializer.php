<?php

namespace App\Serializers;

use League\Fractal\Serializer\ArraySerializer;


class CustomSerializer extends ArraySerializer
{

    public function collection($resourceKey, array $data)
    {
        if ($resourceKey == 'flatten') {
            return  $data;
        }
        return     [$resourceKey ?: 'data' => $data];
    }
}
