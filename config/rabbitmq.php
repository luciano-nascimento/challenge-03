<?php

return [

    'connection' => [
        'host' => env('RABBIT_MQ_HOST'),
        'port' => env('RABBIT_MQ_PORT'),
        'user' => env('RABBIT_MQ_USER'),
        'password' => env('RABBIT_MQ_PASSWORD'),
        'vh' => env('RABBIT_MQ_VH'),
    ]

];
