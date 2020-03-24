<?php

namespace Nksoft\Master\Models;
class Roles extends NksoftModel
{
    protected $table = 'roles';
    protected $fillable = ['id', 'name', 'is_active'];

    public function users()
    {
        return $this->hasOne('\Nksoft\Master\Models\Users', 'role_id', 'id');
    }
}
