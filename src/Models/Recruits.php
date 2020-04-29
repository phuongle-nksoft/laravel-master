<?php
namespace Nksoft\Master\Models;

use Illuminate\Database\Eloquent\Model;

class Recruits extends Model
{
    const FIELDS = ['id', 'file', 'email', 'name', 'phone', 'status'];
    protected $table = 'recruits';
    protected $fillable = self::FIELDS;
}
