<?php

namespace App\Http\Controllers\Site;

use App\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    use \App\ReviewTrait;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData = $request->all();
        $requestData['title'] = $requestData['author_name'] . '-' . date('Y-m-d');
        $review = Review::create($requestData);

        $this->changeItemRating($requestData['item_id']);

        $request->session()->flash('success', 'Отзыв отправлен и будет опубликован после проверки модератором.');
        return redirect()->back();
    }
}
