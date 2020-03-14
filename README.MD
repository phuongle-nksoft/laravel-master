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
    Nksoft\Master\NkSoftMasterServiceProvider::class
]
```

### Add kernel file app/Http/Middleware/Kernel.php

```php
<?php
protected $routeMiddleware = [
    ...
    'nksoft' => \NkSoft\Master\Middleware\CheckAuthorMiddleware::class,
];
```

### Publishes file

```shell
php artisan vendor:publish --tag=public --force
```

## License

Released under the MIT License attached with this code.
