<?php

namespace Nksoft\Master\Models;

class Users extends NksoftModel
{

    protected $table = 'users';

    protected $fillable = ['id', 'is_active', 'role_id', 'name', 'email', 'password', 'phone', 'birthday', 'area', 'image'];

    public function images()
    {
        return $this->hasMany('\Nksoft\Master\Models\FilesUpload', 'parent_id')->where(['type' => 'users']);
    }
}
