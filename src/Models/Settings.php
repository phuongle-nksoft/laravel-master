<?php

namespace Nksoft\Master\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $talbe = 'settings';
    public function images()
    {
        return $this->hasMany('\Nksoft\Master\Models\FilesUpload', 'parent_id')->where(['type' => 'settings']);
    }
}
