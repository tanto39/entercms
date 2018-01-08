<?php

namespace App\Http\Controllers\Site;

use App;
use App\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    use \App\ImgController;
    use \App\PropEnumController;
    use \App\HandlePropertyController;

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
        $showReviews = "N";

        $uri = url()->getRequest()->path();

        $item = new Item();

        // Show reviews
        if ($uri == "reviews") {
            $item = $item->with('reviews');
            $showReviews = "Y";
        }

        $item = $item->where('slug', $slug)->where('category_id', 0)->get()->toArray();

        if(isset($item[0])) {
            $item = $item[0];
        }
        else
            App::abort(404);

        if(isset($item['preview_img']))
            $item['preview_img'] = $this->createPublicImgPath(unserialize($item['preview_img']));

        if(isset($item['properties']))
            $item['properties'] = $this->handlePropertyForPublic($item['properties']);

        return view('public/items/item', [
            'result' => $item,
            'showReviews' => $showReviews
        ]);
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
        $showReviews = "Y";
        $item = $this->getItem($category_slug, $item_slug, 0);

        return view('public/items/item', [
            'result' => $item,
            'showReviews' => $showReviews
        ]);

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

        return view('public/products/item', [
            'result' => $item
        ]);

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
        $item = Item::with([
            'reviews' => function($query) {
                $query->where('published', 1);
            },
            'category' => function($query) {
                global $cat_slug;
                $query->where('slug', $cat_slug);
            }
        ])
        ->where('slug', $item_slug)->where('is_product', $isProduct)->get()->toArray();

        if (isset($item[0]) && !is_null($item[0]['category']))
            $item = $item[0];
        else
            App::abort(404);

        if(isset($item['preview_img']))
            $item['preview_img'] = $this->createPublicImgPath(unserialize($item['preview_img']));

        if(isset($item['properties']))
            $item['properties'] = $this->handlePropertyForPublic($item['properties']);

        return $item;
    }
}
