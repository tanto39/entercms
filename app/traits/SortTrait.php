<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

trait SortTrait
{
    /**
     * Sort items array
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
     * Array paginate
     *
     * @param Request $request
     * @param $items
     * @return \Illuminate\Support\HtmlString
     */
    public function arrayPaginate(Request $request, $items)
    {
        $page = $request->input('page', 1);
        $itemsForCurrentPage = $this->getCurrentPageItems($request, $items);
        $result = new LengthAwarePaginator($itemsForCurrentPage, count($items), PAGE_COUNT, $page);
        $result->setPath(url()->getRequest()->getRequestUri());

        $itemsLink = $result->links();

        return $itemsLink;
    }

    /**
     * Get items for current page
     * @param Request $request
     * @param $items
     * @return array
     */
    public function getCurrentPageItems(Request $request, $items)
    {
        $itemsForCurrentPage = [];

        $page = $request->input('page', 1);
        $offSet = ($page * PAGE_COUNT) - PAGE_COUNT;
        $itemsForCurrentPage = array_slice($items, $offSet, PAGE_COUNT, true);

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
