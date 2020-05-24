<?php
namespace Nksoft\Master\Models;

class Recruits extends NksoftModel
{
    const FIELDS = ['id', 'file', 'email', 'name', 'phone', 'status'];
    protected $table = 'recruits';
    protected $fillable = self::FIELDS;
}
