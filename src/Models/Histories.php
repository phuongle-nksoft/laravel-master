<?php
namespace Nksoft\Master\Models;

use Illuminate\Database\Eloquent\Model;

class Histories extends Model
{
    protected $table = 'histories';
    protected $fillable = ['id', 'parent_id', 'type', 'user_id'];

    public function user()
    {
        return $this->belongsTo('\Nksoft\Master\Models\Users', 'user_id');
    }
}
