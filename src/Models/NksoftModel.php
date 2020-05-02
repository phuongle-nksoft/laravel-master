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

    public function histories()
    {
        return $this->hasOne('\Nksoft\Master\Models\Histories', 'parent_id')->where(['type' => $this->table]);
    }

    /**
     * Get list category by menu
     */
    public static function GetListByMenu($result, $type)
    {
        $parentId = $result->url_to ?? 0;
        $data = array();
        $fs = self::where(['is_active' => 1])->orderBy('order_by')->get();
        if ($fs) {
            foreach ($fs as $item) {
                $selected = array(
                    'opened' => false,
                    'selected' => $item->id === $parentId && $type === $result->type ? true : false,
                );
                $data[] = array(
                    'name' => $item->name,
                    'icon' => 'fas fa-folder',
                    'id' => $item->id,
                    'type' => $type,
                    'state' => $selected,
                    'children' => null,
                    'slug' => $item->slug,
                );
            }
        }
        return $data;
    }

    /**
     * get list id children level
     */
    public static function GetListIds($where, &$data = array())
    {
        $result = self::where($where)->where(['is_active' => 1])->get();
        if ($result) {
            foreach ($result as $item) {
                $data[] = $item->id;
                self::GetListIds(['parent_id' => $item->id], $data);
            }
        }
        return $data;
    }

    /**
     * Get list category to product
     */
    public static function GetListWithParentByMenu($where, $result, $type)
    {
        $parentId = $result->url_to ?? 0;
        $data = array();
        $fs = self::where($where)->where(['is_active' => 1])->orderBy('order_by')->get();
        if ($fs) {
            foreach ($fs as $item) {
                $selected = array(
                    'opened' => false,
                    'selected' => $item->id == $parentId && $result->type == $type ? true : false,
                );
                $data[] = array(
                    'name' => $item->name,
                    'icon' => 'fas fa-folder',
                    'type' => $type,
                    'id' => $item->id,
                    'state' => $selected,
                    'children' => self::GetListWithParentByMenu(['parent_id' => $item->id], $result, $type),
                    'slug' => $item->slug,
                );
            }
        }
        return $data;
    }
}
