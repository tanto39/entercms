<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 12.11.17
 * Time: 16:15
 *
 * Load and delete images
 */

namespace App;


class ImgHelper
{
    /**
     * Load images
     *
     * @param $images - images from request
     * @param $arImage - images from DB
     * @return bool|string
     */
    public static function LoadImg($images, $arImage)
    {
        if(empty($arImage))
            $arImage = [];

        foreach($images as $image) {
            $imgExtension = $image->getClientOriginalExtension();

            // Validate
            if (
                (($imgExtension != 'jpg') &&
                    ($imgExtension != 'jpeg') &&
                    ($imgExtension != 'png') &&
                    ($imgExtension != 'gif') &&
                    ($imgExtension != 'svg')) || ($image->getSize()) > 3000000
            )
                return false;

            // Move image
            $imageName = str_replace('.', '-', $image->getClientOriginalName())
                .time().'.'.$imgExtension;
            $image->move(public_path('images/shares/previews'), $imageName);
            $arImage[] = $imageName;
        }

        return serialize($arImage);
    }

    /**
     * Delete image on server
     *
     * @param $image
     */
    public static function deleteImg($image)
    {
        $sem = sem_get(1);
        if ( sem_acquire($sem) && file_exists($image) ) @unlink($image);
        sem_remove($sem);
    }
}