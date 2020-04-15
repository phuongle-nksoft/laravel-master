<?php
return [
    'status' => [
        ['id' => 1, 'name' => 'Enable'],
        ['id' => 0, 'name' => 'Disable'],
    ],
    'area' => [
        ['id' => 'mn', 'name' => 'Miền Nam'],
        ['id' => 'mt', 'name' => 'Miền Trung'],
        ['id' => 'mb', 'name' => 'Miền Bắc'],
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
