<?php
return [
    'status' => [
        ['id' => 0, 'name' => 'Disable'],
        ['id' => 1, 'name' => 'Enable'],
    ],
    'area' => [
        ['id' => 'mn', 'name' => 'Miền Nam'],
        ['id' => 'mb', 'name' => 'Miền Bắc'],
        ['id' => 'mt', 'name' => 'Miền Trung'],
    ],
    'providers' => [
        Intervention\Image\ImageServiceProvider::class,
    ],
    'aliases' => [
        'Image' => Intervention\Image\Facades\Image::class,
    ],
];
