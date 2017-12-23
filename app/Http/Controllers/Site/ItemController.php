<?php

namespace App\Http\Controllers\Site;

use App;
use App\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    public $indexRoute = 'item.index';
    public $prefix = 'Item';


    /**
     * Display uncategorised item.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function showUncategorisedItem($slug)
    {
        $item = [];

        $item = Item::with('reviews')->where('slug', $slug)->where('category_id', 0)->get();

        if(isset($item[0])) {
            return view('public/items/item', [
                'result' => $item[0]
            ]);
        }
        else
            App::abort(404);
    }

    /**
     * Show blog items
     *
     * @param string $category_slug
     * @param string $item_slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showBlogItem($category_slug, $item_slug)
    {
        $item = [];

        global $cat_slug;
        $cat_slug = $category_slug;

        // Get item with reviews and categories
        $item = Item::with(['reviews', 'category' => function($query) {
            global $cat_slug;
            $query->where('slug', $cat_slug);
        }])
        ->where('slug', $item_slug)->where('is_product', 0)->get();

        if(isset($item[0]) && !is_null($item[0]->category)) {
            return view('public/items/item', [
                'result' => $item[0]
            ]);
        }
        else
            App::abort(404);
    }
}
