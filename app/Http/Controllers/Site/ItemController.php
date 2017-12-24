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
     * Show blog item
     *
     * @param string $category_slug
     * @param string $item_slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showBlogItem($category_slug, $item_slug)
    {
        $item = $this->getItem($category_slug, $item_slug, 0);

        if(isset($item[0]) && !is_null($item[0]->category)) {
            return view('public/items/item', [
                'result' => $item[0]
            ]);
        }
        else
            App::abort(404);
    }

    /**
     * Show product
     *
     * @param string $category_slug
     * @param string $item_slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showProduct($category_slug, $item_slug)
    {
        $item = $this->getItem($category_slug, $item_slug, 1);

        if(isset($item[0]) && !is_null($item[0]->category)) {
            return view('public/products/item', [
                'result' => $item[0]
            ]);
        }
        else
            App::abort(404);
    }

    /**
     * Get item
     *
     * @param $category_slug
     * @param $item_slug
     * @param $isProduct
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getItem($category_slug, $item_slug, $isProduct)
    {
        $item = [];

        global $cat_slug;
        $cat_slug = $category_slug;

        // Get item with reviews and categories
        $item = Item::with(['reviews', 'category' => function($query) {
            global $cat_slug;
            $query->where('slug', $cat_slug);
        }])
        ->where('slug', $item_slug)->where('is_product', $isProduct)->get();

        return $item;
    }
}
