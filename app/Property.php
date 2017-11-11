<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use AdminPanel;
    /**
     * Fields white list
     */
    protected $fillable = [
        'published',
        'title',
        'preview_img',
        'order',
        'slug',
        'meta_key',
        'meta_desc',
        'description',
        'full_content',
        'type',           // values: html, img, int, str, category_link, item_link
        'is_insert',      // values: Y, N
        'prop_kind',       // values: category, item
        'category_id',
        'group_id'
    ];
}
