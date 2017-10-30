<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    /**
     * Fields white list
     */
    protected $fillable = [
        'published',
        'title',
        'order',
        'slug',
        'meta_key',
        'meta_desc',
        'description',
        'full_content',
        'created_by',
        'modify_by',
        'parent_id'
    ];

    /**
     * Set slug (uri) if empty
     * @param $slug
     */
    public function setSlugAttribute($slug) {
        if (empty($slug)) {
            $this->attributes['slug'] = Str::slug(mb_substr($this->title . "-" . $this->id, 0, 100), '-');
        }
        else {
            $this->attributes['slug'] = $slug;
        }
    }

    /**
     * Set order if empty
     * @param $order
     */
    public function setOrderAttribute($order) {
        if (empty($order)) {
            $this->attributes['order'] = 10000;
        }
        else {
            $this->attributes['order'] = $order;
        }
    }

    /**
     * Get children category
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children() {
        return $this->hasMany(self::class, 'parent_id');
    }
}
