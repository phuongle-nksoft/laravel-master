<?php

namespace Nksoft\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Users extends Model
{
    use SoftDeletes;

    protected $table = 'users';

    protected $fillable = ['is_active', 'role_id', 'name', 'email', 'password', 'phone', 'birthday', 'area', 'image'];
}
