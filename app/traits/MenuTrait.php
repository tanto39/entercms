<?php

namespace App;

use App\MenuItem;
use Illuminate\Support\Facades\Auth;

trait MenuTrait
{
    /**
     * Destroy item menu with menu delete
     *
     * @param $fieldName
     * @param $fieldValue
     */
    public function deleteMenuItem($fieldName, $fieldValue)
    {
        $menuItems = [];
        $arMenuItemId = [];
        $menuItems = MenuItem::where($fieldName, $fieldValue)->select(['id'])->get()->toArray();

        if (!empty($menuItems)) {
            foreach ($menuItems as $menuItem) {
                $arMenuItemId[] = $menuItem['id'];
            }
            MenuItem::destroy($arMenuItemId);
        }
    }

    /**
     * Generate menu href
     *
     * @param $requestData
     * @return string
     */
    public function generateHref($requestData)
    {
        $href = '';

        // Category
        if ($requestData['type'] == MENU_TYPE_CATEGORY && isset($requestData['link_id']))
        {
            $category = Category::where('id', $requestData['link_id'])->select(['id', 'slug', 'catalog_section'])->get()->toArray()[0];

            if ($category['catalog_section'] == 1) {
                if ($category['id'] == CATALOG_ID)
                    $href = '/' . CATALOG_SLUG;
                else
                    $href = '/' . CATALOG_SLUG . '/' . $category['slug'];
            }
            else {
                if ($category['id'] == BLOG_ID)
                    $href = '/' . BLOG_SLUG;
                else
                    $href = '/' . BLOG_SLUG . '/' . $category['slug'];
            }
        }
        // Item
        elseif ($requestData['type'] == MENU_TYPE_ITEM && isset($requestData['link_id'])) {
            $item = Item::where('id', $requestData['link_id'])->select(['id', 'slug', 'is_product', 'category_id'])->get()->toArray()[0];
            $category = Category::where('id', $item['category_id'])->select(['id', 'slug'])->get()->toArray();

            if(!empty($category)) {
                if ($item['is_product'] == 1)
                    $href = '/' . CATALOG_SLUG . '/' . $category[0]['slug'] . '/' . $item['slug'];
                else
                    $href = '/' . BLOG_SLUG . '/' . $category[0]['slug'] . '/' . $item['slug'];
            }
            else {
                $href = '/' . $item['slug'];
            }
        }
        else
            $href = $requestData['href'];

        return $href;
    }
}
