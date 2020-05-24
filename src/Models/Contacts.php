<?php

namespace Nksoft\Master\Models;

class Contacts extends NksoftModel
{
    const FIELDS = ['id', 'email', 'name', 'status', 'note', 'phone'];
    protected $table = 'contacts';
    protected $fillable = self::FIELDS;
}
