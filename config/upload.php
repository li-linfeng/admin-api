<?php

return [

    'default' => 'local',
    'drivers' => [
        'local' => App\Admin\Services\UploadHandlers\LocalUploader::class,
    ]
];
