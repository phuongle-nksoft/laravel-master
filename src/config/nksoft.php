<?php
return [
    'status' => [
        ['id' => 1, 'name' => 'Enable'],
        ['id' => 0, 'name' => 'Disable'],
    ],
    'area' => [
        ['id' => 1, 'name' => 'Miền Nam'],
        ['id' => 2, 'name' => 'Miền Trung'],
        ['id' => 3, 'name' => 'Miền Bắc'],
    ],
    'social' => [
        ['id' => 'fb', 'name' => 'Facebook'],
        ['id' => 'gg', 'name' => 'Google'],
        ['id' => 'tw', 'name' => 'Twitter'],
        ['id' => 'zl', 'name' => 'Zalo'],
    ],
    'providers' => [
        Intervention\Image\ImageServiceProvider::class,
    ],
    'aliases' => [
        'Image' => Intervention\Image\Facades\Image::class,
    ],
];
