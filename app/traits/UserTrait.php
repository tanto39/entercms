<?php

namespace App;

use App\Item;
use App\Review;
use App\Category;
use Illuminate\Support\Facades\Auth;

trait UserTrait
{
    /**
     * Change fields created_by and modify_by for items, categories, reviews
     *
     * @param $userId
     */
    public function changeCreatedModifyBy($userId)
    {
        $arField = ['created_by', 'modify_by'];

        foreach ($arField as $field) {
            $review = Review::where($field, $userId)->update([$field => null]);
            $item = Item::where($field, $userId)->update([$field => null]);
            $catecory = Category::where($field, $userId)->update([$field => null]);
        }
    }
}
