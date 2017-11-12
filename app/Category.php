<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use AdminPanel;

    /**
     * Fields white list
     */
    protected $fillable = [
        'published',
        'title',
        'order',
        'preview_img',
        'slug',
        'meta_key',
        'meta_desc',
        'description',
        'full_content',
        'created_by',
        'modify_by',
        'parent_id',
        'properties'
    ];

    /**
     * Get children category
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children() {
        return $this->hasMany(self::class, 'parent_id');
    }

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
        $arImage = ImgHelper::LoadImg($images, $oldImages);

        if($arImage)
            $this->attributes['preview_img'] = $arImage;
    }
}
