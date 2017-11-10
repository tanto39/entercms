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
        'type',
        'category_id'
    ];
}
