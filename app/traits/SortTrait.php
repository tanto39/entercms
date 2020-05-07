<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

trait SortTrait
{
    /**
     * Sort items array (deprecated, not used)
     *
     * @param $items
     * @param $arSortProp
     * @param Request $request
     * @return mixed
     */
    public function setSortByProps($items, $arSortProp, Request $request)
    {
        foreach ($items as $key => $item) {

            if (!empty($item["properties"])) {

                foreach ($item["properties"] as $groupName => $arPropsFromItem) {
                    // Set max or min sort value if it's empty for put them to the end of items list
                    if ($arSortProp[1] == 'up')
                        $items[$key]["sortValue"] = 1000000000;
                    else
                        $items[$key]["sortValue"] = 1;

                    if (!empty($arPropsFromItem) && array_key_exists($arSortProp[2], $arPropsFromItem))
                        $items[$key]["sortValue"] = $arPropsFromItem[$arSortProp[2]]['value'];
                }
            }
        }

        // Sort items by sortValue
        if ($arSortProp[1] == 'up')
            uasort($items, ['self', 'sortArrayUp']);
        else
            uasort($items, ['self', 'sortArrayDown']);

        return $items;
    }

    /**
     * @param $requestUri
     * @param $page
     * @param $countItems
     * @return array
     */
    public function setPaginate($requestUri, $page, $countItems = 0)
    {
        $itemLinks = [];
        $itemLinks['is_first_page'] = 'N';
        $itemLinks['is_last_page'] = 'N';

        $pageStr = '?page=';
        if ((strpos($requestUri, 'setfilter='))
            || (strpos($requestUri, 'setsortCategoryPublic=')))
            $pageStr = '&page=';

        $requestUriWithoutPage = explode($pageStr, $requestUri)[0];

        $countPages = ceil($countItems / PAGE_COUNT);

        if ($page == 1)
            $itemLinks['is_first_page'] = 'Y';

        for ($i = 1; $i <= $countPages; $i++) {
            $itemLinks['pages'][$i]['url'] = $requestUriWithoutPage;

            if ($i != 1)
                $itemLinks['pages'][$i]['url'] .= $pageStr . $i;

            if ($i == $page) {
                $itemLinks['pages'][$i]['active'] = 'Y';
            } else {
                $itemLinks['pages'][$i]['active'] = 'N';
            }
        }

        $itemLinks['last_page'] = $i-1;

        if ($page ==  $itemLinks['last_page'])
            $itemLinks['is_last_page'] = 'Y';

        return($itemLinks);
    }

    /**
     * Get items for current page
     * @param Request $request
     * @param $items
     * @return array
     */
    public function getCurrentPageItems(Request $request, $items, $page, $countItems = 0)
    {
        $itemsForCurrentPage = [];

        $offset = ($page * PAGE_COUNT) - PAGE_COUNT;
        $items = $items->offset($offset)->limit(PAGE_COUNT);
        $itemsForCurrentPage = $items->get();
        return $itemsForCurrentPage;
    }

    /**
     * Get properties for sort
     *
     * @return mixed
     */
    public function getAllSortProperties()
    {
        $properties = Property::whereIn('id', AR_PROP_SORT)->where('prop_kind', PROP_KIND_ITEM)
            ->orderby('order', 'asc')
            ->select(['id', 'order', 'title', 'slug', 'type'])->get()->toArray();

        return $properties;
    }

    /**
     * @param $a
     * @param $b
     * @return bool
     */
    public static function sortArrayUp($a, $b) {
        return ($a['sortValue'] > $b['sortValue']);
    }

    /**
     * @param $a
     * @param $b
     * @return bool
     */
    public static function sortArrayDown($a, $b) {
        return ($a['sortValue'] < $b['sortValue']);
    }
}
