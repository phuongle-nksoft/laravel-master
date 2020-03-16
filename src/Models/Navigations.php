<?php

namespace Nksoft\Master\Models;

use Illuminate\Database\Eloquent\Model;

class Navigations extends Model
{
    protected $table = 'navigations';
    //
    public static function saveItem(array $items)
    {
        foreach ($items as $item) {
            $save = self::where(['title' => $item['title']])->first();
            if ($save == null) {
                $save = new self();
            }
            foreach ($item as $k => $v) {
                $save->$k = $v;
            }

            $save->save($item);
        }
    }
}
