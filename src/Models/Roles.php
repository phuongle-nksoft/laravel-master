<?php

namespace Nksoft\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Roles extends Model
{
    use SoftDeletes;
    protected $table = 'roles';
    protected $fillable = ['id', 'name', 'is_active'];

    public function users()
    {
        return $this->hasOne('\Nksoft\Master\Models\Users', 'role_id', 'id');
    }
}
