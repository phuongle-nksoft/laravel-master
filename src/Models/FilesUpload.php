<?php

namespace Nksoft\Master\Models;

use Illuminate\Database\Eloquent\Model;

class FilesUpload extends Model
{
    protected $table = 'files_uploads';
    protected $fillable = ['image', 'name', 'description', 'parent_id', 'type'];
}
