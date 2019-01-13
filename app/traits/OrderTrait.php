<?php

namespace App;

use App\Item;
use App\Category;
use App\Order;
use Illuminate\Support\Facades\Auth;

trait OrderTrait
{
    public $price = 0;
    public $title = '';

    /**
     * Get product list in basket
     *
     * @param $arToBasket
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder|static
     */
    public function getProductList($arToBasket)
    {
        $items = Item::with('category');

        foreach ($arToBasket as $productId=>$arItem) {
            $items = $items->orwhere('id', $productId);
        }

        $items = $items->orderby('order', 'asc')->orderby('updated_at', 'desc')
            ->select([
                'id',
                'title',
                'preview_img',
                'order',
                'rating',
                'slug',
                'description',
                'properties',
                'category_id'
            ])->get();

        $items = $this->handleItemsArray($items);

        foreach ($items as $key=>$item) {
            $items[$key]['quantity'] = $arToBasket[$item['id']]['quantity'];

            if (isset($item['properties'][PROP_GROUP_NAME_ALL][PROP_PRICE_ID]['value'])) {
                $items[$key]['fullprice'] = (int)$items[$key]['properties'][PROP_GROUP_NAME_ALL][PROP_PRICE_ID]['value'] * (int)$arToBasket[$item['id']]['quantity'];
                $items[$key]['price'] = (int)$items[$key]['properties'][PROP_GROUP_NAME_ALL][PROP_PRICE_ID]['value'];
            }
            else {
                $items[$key]['fullprice'] = 0;
                $items[$key]['price'] = 0;
            }

            $this->price += $items[$key]['fullprice'];
            $this->title .= $item['title'] . '-';
        }

        return $items;
    }

    /**
     * Set basket array for admin panel
     *
     * @param $arQuattity
     * @return string
     */
    public function setQuantityAdmin($arQuattity)
    {
        $arToBasket = [];

        foreach($arQuattity as $productId=>$quantity) {
            $arToBasket[(int)$productId] = [
                'id' => (int)$productId,
                'quantity' => (int)$quantity
            ];
        }

        return serialize($arToBasket);
    }
}
