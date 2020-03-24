## Installation

Run the command below to install via Composer

```shell
composer require nksoft/laravel-master
```

## Getting Started

### Add config file config/app.php

```php
<?php
'providers' => [
    ...
    Nksoft\Master\NkSoftMasterServiceProvider::class,
    Intervention\Image\ImageServiceProvider::class,
]
'aliases' => [
    ...
    'Image' => Intervention\Image\Facades\Image::class,
]
```

### Add kernel file app/Http/Middleware/Kernel.php

```php
<?php
protected $routeMiddleware = [
    ...
    'nksoft' => \Nksoft\Master\Middleware\CheckAuthorMiddleware::class,
];
```

### Migrate package

```shell
php artisan migrate
```

### DB seeder package

```shell
php artisan db:seed --class="\Nksoft\Master\database\seeds\NksoftSeeder"
```

### Publishes file

```shell
php artisan vendor:publish --tag=nksoft --force
```

### Syslink storage file

```shell
php artisan storage:link
```

## License

Released under the MIT License attached with this code.
