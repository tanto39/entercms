<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait AdminPanel
{
    use ImgController;
    use HandlePropertyController;

    /**
     * Set slug (uri) if empty
     * @param $slug
     */
    public function setSlugAttribute($slug)
    {
        if (empty($slug)) {
            $slugResult = Str::slug(mb_substr($this->title . "-" . $this->id, 0, 100), '-');
        }
        else {
            $slugResult = $slug;
        }

        // Check unique slug
        $obSlug = $this->select(['id', 'slug'])->where('slug', $slugResult)->where('id', '!=', $this->id)->get();

        if ((count($obSlug) > 0) && ($obSlug->pluck('slug')[0] == $slugResult))
            $slugResult .= time();

        $this->attributes['slug'] = $slugResult;
    }

    /**
     * Set order if empty
     * @param $order
     */
    public function setOrderAttribute($order)
    {
        if (empty($order)) {
            $this->attributes['order'] = 10000;
        }
        else {
            $this->attributes['order'] = $order;
        }
    }
}
