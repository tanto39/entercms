<?php

namespace App;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

trait ImgController
{
    public $propImages = [];

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
        $arImage = serialize($this->LoadImg($images, $oldImages));

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
    public function deleteMultiplePrevImg(Request $request, $selectTable, $imgField, $imgDirectory = PREV_IMG_FULL_PATH)
    {
        $obImage = $selectTable->select(['id', $imgField])->where('id', $selectTable->id)->get();
        $arImage = unserialize($obImage->pluck($imgField)[0]);

        // Delete from server and array
        $arImage = $this->deleteMultipleImg($arImage, $request->deleteImg);

        // Delete image from database
        $arImage = serialize($arImage);
        $selectTable->where('id', $selectTable->id)->update([$imgField => $arImage]);

        return $selectTable;
    }

    /**
     * Delete image property value
     *
     * @param Request $request
     * @param $selectTable
     * @param string $imgDirectory
     * @return mixed
     */
    public function deleteMultiplePropImg(Request $request, $selectTable, $imgDirectory = PREV_IMG_FULL_PATH)
    {
        $strProps = $selectTable->select(['id', 'properties'])->where('id', $selectTable->id)->get()->toArray()[0]['properties'];
        $arPropsGrous = unserialize($strProps);

        foreach ($arPropsGrous as $groupName => $arProps) {
            foreach ($arProps as $key => $arProp) {
                if ($arProp['type'] == PROP_TYPE_IMG) {
                    // Delete from server and array
                    $arPropsGrous[$groupName][$key]['value'] = $this->deleteMultipleImg($arProp['value'], $request->deletePropImg);
                }
            }
        }

        $strProps = serialize($arPropsGrous);
        $selectTable->where('id', $selectTable->id)->update(['properties' => $strProps]);

        return $selectTable;
    }

    /**
     * Delete multiple images
     *
     * @param $arImage
     * @param $requestDelFileName
     * @param string $imgDirectory
     * @return mixed
     */
    public function deleteMultipleImg($arImage, $requestDelFileName, $imgDirectory = PREV_IMG_FULL_PATH)
    {
        foreach ($arImage as $key => $image) {
            if ($image['MIDDLE'] == $requestDelFileName) {
                unset($arImage[$key]);

                // Delete image on server
                $this->deleteImgFromServer($image);
            }
        }

        return $arImage;
    }

    /**
     * Delete image from server
     *
     * @param $image
     * @param string $imgDirectory
     */
    public function deleteImgFromServer($image, $imgDirectory = PREV_IMG_FULL_PATH)
    {
        $imgPath = public_path($imgDirectory . $image['FULL']);
        $middleImgPath = public_path($imgDirectory . $image['MIDDLE']);
        $smallImgPath = public_path($imgDirectory . $image['SMALL']);

        $this->deleteImg($imgPath);
        $this->deleteImg($middleImgPath);
        $this->deleteImg($smallImgPath);
    }

    /**
     * Load images
     *
     * @param $images - images from request
     * @param $arImage - images from DB
     * @param $imgDirectory
     * @param $fullWidth
     * @param $smallWidth
     * @param $middleWidth
     * @return array
     */
    public function LoadImg($images, $arImage, $imgDirectory = PREV_IMG_FULL_PATH, $fullWidth = PREV_IMG_FULL_WIDTH, $smallWidth = PREV_IMG_SMALL_WIDTH, $middleWidth = PREV_IMG_MIDDLE_WIDTH)
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
            $time = time();

            $imageName = str_replace('.', '-', $image->getClientOriginalName())
                . $time .'.'.$imgExtension;

            $middleImgName = str_replace('.', '-', 'middle-' . $image->getClientOriginalName())
                . $time .'.'.$imgExtension;

            $smallImgName = str_replace('.', '-', 'small-' . $image->getClientOriginalName())
                . $time .'.'.$imgExtension;

            $image_resize = Image::make($image->getRealPath());

            // Resize and save
            // Full
            $image_resize->widen($fullWidth, function ($constraint) {
                $constraint->upsize();
            });
            $image_resize->save(public_path($imgDirectory . $imageName));

            // Middle
            $image_resize->widen($middleWidth, function ($constraint) {
                $constraint->upsize();
            });
            $image_resize->save(public_path($imgDirectory . $middleImgName));

            // Small
            $image_resize->widen($smallWidth, function ($constraint) {
                $constraint->upsize();
            });
            $image_resize->save(public_path($imgDirectory . $smallImgName));

            $arImage[] = [
                'FULL' => $imageName,
                'MIDDLE' => $middleImgName,
                'SMALL' => $smallImgName
            ];
        }

        return $arImage;
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
     * @param $imgDirectory
     */
    public function deleteImgWithDestroy($selectTable, $imgField, $imgDirectory = PREV_IMG_FULL_PATH)
    {
        $arPropsGrous = [];
        $arProps = [];
        $arImage = [];

        // Delete preview images
        $obImage = $selectTable->select(['id', $imgField, 'properties'])->where('id', $selectTable->id)->get();
        $arImage = unserialize($obImage->pluck($imgField)[0]);
        $arPropsGrous = unserialize($obImage->pluck('properties')[0]);

        if ($arPropsGrous && count($arPropsGrous) > 0) {
            foreach ($arPropsGrous as $groupName => $arProps) {
                foreach ($arProps as $key => $arProp) {
                    if ($arProp['type'] == PROP_TYPE_IMG) {
                        foreach ($arProp['value'] as $keyImg => $arDelImg)
                            $this->deleteImgFromServer($arDelImg);
                    }
                }
            }
        }

        if ($arImage && count($arImage) > 0) {
            foreach ($arImage as $key => $image) {
                $this->deleteImgFromServer($image);
            }
        }
    }

    /**
     * Create puublic image path
     *
     * @param $arImage
     * @return mixed
     */
    public function createPublicImgPath($arImage)
    {
        foreach ($arImage as $key=>$image) {
            $arImage[$key]['FULL'] = '/' . PREV_IMG_FULL_PATH . $image['FULL'];
            $arImage[$key]['MIDDLE'] = '/' . PREV_IMG_FULL_PATH . $image['MIDDLE'];
            $arImage[$key]['SMALL'] = '/' . PREV_IMG_FULL_PATH . $image['SMALL'];
        }

        return $arImage;
    }
}
