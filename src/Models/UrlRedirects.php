<?php

namespace Nksoft\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UrlRedirects extends Model
{
    use SoftDeletes;
    protected $talbe = 'url_redirects';
    protected $fillable = ['id', 'url_original', 'url_path'];
}
