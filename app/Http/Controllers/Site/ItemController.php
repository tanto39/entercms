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
    public $cat_slug = '';

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

        $template = TemplateController::getInstance();
        if($template->isInstance == 'N') $template->setTemplateVariables();

        $item = new Item();

        // Show reviews
        if ($template->uri == "reviews") {
            $item = $item->with(['reviews' => function($query) {
                $query->where('published', 1);
            }]);
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
            'showReviews' => $showReviews,
            'template' => $template
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

        $template = TemplateController::getInstance();
        if($template->isInstance == 'N') $template->setTemplateVariables();

        return view('public/items/item', [
            'result' => $item,
            'showReviews' => $showReviews,
            'template' => $template
        ]);

    }

    /**
     * Show product
     *
     * @param string $category_slug
     * @param string $item_slug
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showProduct($category_slug, $item_slug, Request $request)
    {
        $template = TemplateController::getInstance();
        if($template->isInstance == 'N') $template->setTemplateVariables();

        $item = $this->getItem($category_slug, $item_slug, 1);

        $arToBasket = unserialize($request->cookie('basket'));

        $inBasket = 'N';

        if(isset($arToBasket) && !empty($arToBasket)) {
            foreach ($arToBasket as $keyId=>$arBasket) {
                if ($item['id'] == $keyId)
                    $inBasket = 'Y';
            }
        }

        return view('public/products/item', [
            'result' => $item,
            'inBasket' => $inBasket,
            'template' => $template
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
        $this->cat_slug = $category_slug;

        // Get item with reviews and categories
        $item = Item::with([
            'reviews' => function($query) {
                $query->where('published', 1);
            },
            'category' => function($query) {
                $query->where('slug', $this->cat_slug);
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
