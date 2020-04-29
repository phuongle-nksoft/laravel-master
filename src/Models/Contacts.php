<?php

namespace Nksoft\Master\Models;

use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    const FIELDS = ['id', 'email', 'name', 'status', 'note', 'phone'];
    protected $table = 'contacts';
    protected $fillable = self::FIELDS;
}
