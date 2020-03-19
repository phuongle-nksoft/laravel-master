<?php

if (!function_exists('putUploadImage')) {
    function putUploadImage($image, $filename, $resize = false)
    {
        $small = array(120, 120);
        $image = @file_get_contents($image);
        $path = "media/" . $filename;
        if (!is_dir(storage_path("public/media/"))) {
            \Storage::makeDirectory("public/media/");
        }

        \Storage::put(
            $path, $image, 'public'
        );
        if ($resize == true) {
            $pathSmall = "media/small/" . $filename;
            \Storage::put(
                $pathSmall, $image, 'public'
            );
            resizeImage($small, $pathSmall);
        }
        return $path;
    }
}

if (!function_exists('deleteImage')) {
    function deleteImage($path, $default = true)
    {
        if ($default == true) {
            $small = str_replace("media/", "media/small/", $path);
            \Storage::delete($thumb);
            \Storage::delete($small);
        }
        return \Storage::delete($path);
    }
}

if (!function_exists('getImage')) {
    function getImage($path)
    {
        if (is_file(base_path("storage/app/public/" . $path))) {
            return \Storage::url($path);
        }

        return "/images/no-image.svg";
    }
}

if (!function_exists('getImageSmall')) {
    function getImageSmall($path, $size = "smalll")
    {
        $path = str_replace("media/", "media/$size/", $path);
        return \Storage::url($path);
    }
}

if (!function_exists('resizeImage')) {
    function resizeImage($size = array(200, 200), $thumb)
    {
        if (is_file(base_path("storage/app/" . $thumb))) {
            $img = \Image::make(base_path("storage/app/" . $thumb))->resize($size[0], $size[1]);
            $img->save(base_path("storage/app/" . $thumb));
            return $img;
        }
        return false;
    }
}
