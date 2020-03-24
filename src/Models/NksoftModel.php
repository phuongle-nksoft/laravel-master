<?php

namespace Nksoft\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class NksoftModel extends Model
{
    use SoftDeletes;
}
