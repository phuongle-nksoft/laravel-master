<?php

namespace Nksoft\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NksoftModel extends Model
{
    use SoftDeletes;

    public function images()
    {
        return $this->hasMany('\Nksoft\Master\Models\FilesUpload', 'parent_id')->where(['type' => $this->table]);
    }
}
