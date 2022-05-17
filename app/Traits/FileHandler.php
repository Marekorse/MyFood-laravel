<?php

namespace App\Traits;

use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait FileHandler
{
    /**
     * store image.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadImage($image, $storage_path, $width = 800, $height = 600, $thumb = false)
    {
        
        if (!is_dir($storage_path)){
            mkdir($storage_path, 0777, true);
        } 

        $image->hashName();
        $name = basename($image->getClientOriginalName(), '.' . $image->getClientOriginalExtension());
        $extension = '.' . $image->getClientOriginalExtension();


        $image = Image::make($image);

        $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        if ($thumb) 
        {
            $image->save($storage_path . '/' . $name . '-thumb' . $extension);
            return $name . '-thumb' . $extension;
        } 
        
        else
        {
            $image->save($storage_path . '/' . $name . $extension);
            return $name . $extension;
        } 
    }
    /**
     * crop image.
     *
     * @return \Illuminate\Http\Response
     */
    public function createAvatar($image,$width=200)
    {
        $image = Image::make($image);
        $image->fit($width);
        $image->save();
    }

    /**
     * delete image.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteImage($path,$Visibility,$thumb_path=false)
    {
        if($thumb_path)
        {
            Storage::delete($Visibility.'/'.$thumb_path);
        }
        
        Storage::delete($Visibility.'/'.$path);
    }
}
