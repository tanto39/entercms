<?php

namespace App;

use App\Item;
use App\Review;

trait ReviewTrait
{
    /**
     * Change item rating
     *
     * @param $itemId
     */
    public function changeItemRating($itemId)
    {
        $ratings = [];
        $countRatings = 0;
        $sumRating = 0;
        $resultRating = 0;

        $ratings = Review::where('item_id', $itemId)->select(['id', 'rating'])->get()->toArray();
        $countRatings = count($ratings);

        if ($countRatings > 0) {
            foreach ($ratings as $rating) {
                $sumRating = $sumRating + $rating['rating'];
            }

            $resultRating = $sumRating/$countRatings;

            Item::where('id', $itemId)->update(['rating' => $resultRating]);
        }
    }
}
