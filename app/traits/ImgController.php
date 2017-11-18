<?php

namespace App;

use Illuminate\Http\Request;

trait ImgController
{
    /**
     * Load preview images
     *
     * @param $images
     */
    public function setPreviewImgAttribute($images)
    {
        $oldImages = [];

        // Images from DB
        $obImage = $this->select(['id', 'preview_img'])->where('id', $this->id)->get();

        if (count($obImage) > 0)
            $oldImages = unserialize($obImage->pluck('preview_img')[0]);

        // Load images
        $arImage = $this->LoadImg($images, $oldImages, 'images/shares/previews');

        if($arImage)
            $this->attributes['preview_img'] = $arImage;
    }

    /**
     * Delete multiple image from DB field and server
     *
     * @param Request $request
     * @param $selectTable
     * @param $imgField
     * @param $imgDirectory
     * @return mixed
     */
    public function deleteMultipleImg(Request $request, $selectTable, $imgField, $imgDirectory)
    {
        $obImage = $selectTable->select(['id', $imgField])->where('id', $selectTable->id)->get();
        $arImage = unserialize($obImage->pluck($imgField)[0]);

        foreach ($arImage as $key => $image) {
            if ($image == $request->deleteImg) {
                unset($arImage[$key]);

                // Delete image on server
                $imgPath = public_path($imgDirectory . $request->deleteImg);
                $this->deleteImg($imgPath);
            }
        }

        // Delete image from database
        $arImage = serialize($arImage);
        $selectTable->where('id', $selectTable->id)->update([$imgField => $arImage]);

        return $selectTable;
    }

    /**
     * Load images
     *
     * @param $images - images from request
     * @param $arImage - images from DB
     * @return bool|string
     */
    public function LoadImg($images, $arImage, $imgDirectory)
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
            $image->move(public_path($imgDirectory), $imageName);
            $arImage[] = $imageName;
        }

        return serialize($arImage);
    }

    /**
     * Delete image on server
     *
     * @param $image
     */
    public function deleteImg($image)
    {
        $sem = sem_get(1);
        if ( sem_acquire($sem) && file_exists($image) ) @unlink($image);
        sem_remove($sem);
    }

    /**
     * Delete images with destroy item
     *
     * @param $selectTable
     * @param $imgField
     * @param $imgDirectoty
     */
    public function deleteImgWithDestroy($selectTable, $imgField, $imgDirectoty)
    {
        // Delete preview images
        $obImage = $selectTable->select(['id', $imgField])->where('id', $selectTable->id)->get();
        $arImage = unserialize($obImage->pluck($imgField)[0]);

        if ($arImage) {
            foreach ($arImage as $key => $image) {
                $imgPath = public_path($imgDirectoty . $image);
                $this->deleteImg($imgPath);
            }
        }
    }
}
