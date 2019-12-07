<?php

namespace App;

use App\Item;
use App\Property;

trait CategoryTrait
{
    /**
     * Destroy with images and properties
     *
     * @param $selectTable
     */
    public function baseDestroy($selectTable)
    {
        // Delete preview images
        $this->deleteImgWithDestroy($selectTable, 'preview_img');

        // Delete download files
        $this->deleteFileWithDestroy($selectTable);

        // Delete properties
        $this->deletePropertyWithDestroy($selectTable);

        // Delete category
        $selectTable->destroy($selectTable->id);
    }

    /**
     * Delete item
     *
     * @param $selectTable
     */
    public function itemDestroy($selectTable)
    {
        $arIdReviews = [];

        $reviews = Review::where('item_id', $selectTable->id)->select(['id'])->get()->toArray();

        foreach ($reviews as $review)
            $arIdReviews[] = $review['id'];

        Review::destroy($arIdReviews);

        $this->baseDestroy($selectTable);
    }

    /**
     * Destroy with children elements
     *
     * @param $selectTable
     */
    public function recurceDestroy($selectTable)
    {
        $childs = $selectTable->where('parent_id', $selectTable->id)->get();

        $items = new Item();
        $items = $items->where('category_id', $selectTable->id)->get();

        // Delete items
        if (isset($items)) {
            foreach ($items as $item)
                $this->ItemDestroy($item);
        }

        // Delete category
        $this->baseDestroy($selectTable);

        if (isset($childs)) {
            foreach ($childs as $child)
                $this->recurceDestroy($child);
        }
    }

    /**
     * Get category
     *
     * @param $category_slug
     * @param $isCatalog
     * @return mixed
     */
    public function getCategory($category_slug, $isCatalog)
    {
        $category = new Category();

        $category = $category->with('children')
            ->where('slug', $category_slug)
            ->where('catalog_section', $isCatalog)
            ->get()->toArray();

        if(isset($category[0])) {
            return $category[0];
        }
        else
            App::abort(404);
    }

    /**
     * Get items
     *
     * @param $category
     * @param $isProduct
     * @param $request
     * @return mixed
     */
    public function getItems($category, $isProduct, $request)
    {
        $items = new Item();

        //get child categories
        $childCategories = Category::where('parent_id', $category['id'])->select(['id'])->get()->toArray();

        $items = $items->with('category');

        if(!empty($childCategories)) {
            foreach ($childCategories as $key=>$arCategory)
                $childCategories[$key] = $arCategory['id'];
            $childCategories[] = $category['id'];
            $items = $items->whereIn('category_id', $childCategories);
        }
        else {
            $items = $items->where('category_id', $category['id']);
        }

        // get items
        $items = $items->where('published', 1)->where('is_product', $isProduct)
            ->select([
                'title',
                'preview_img',
                'order',
                'rating',
                'slug',
                'category_id',
                'description',
                'properties',
            ])
            ->orderby('order', 'asc')->orderby('updated_at', 'desc');

        // Filter
        $items = $this->filterExec($request, $items);

        if ($isProduct == 1)
            $items = $this->smartFilterExec($request, $items);

        return $items;
    }

    /**
     * Handle category images and properties
     *
     * @param $category
     * @return mixed
     */
    public function handleCategoryArray($category)
    {
        if(isset($category['preview_img']))
            $category['preview_img'] = $this->createPublicImgPath(unserialize($category['preview_img']));

        if(isset($category['properties']))
            $category['properties'] = $this->handlePropertyForPublic($category['properties']);

        return $category;
    }

    /**
     * Handle items images and properties
     *
     * @param $items
     * @return mixed
     */
    public function handleItemsArray($items)
    {
        if(!is_array($items))
            $items = $items->toArray();

        foreach ($items as $key=>$item) {
            if(isset($item['preview_img']))
                $items[$key]['preview_img'] = $this->createPublicImgPath(unserialize($item['preview_img']));

            if(isset($item['properties']))
                $items[$key]['properties'] = $this->handlePropertyForPublic($item['properties']);
        }

        return $items;
    }

    /**
     * Handle items images and properties
     *
     * @param $categories
     * @return mixed
     */
    public function handleCategoriesArray($categories)
    {
        foreach ($categories as $key=>$category) {
            if(isset($category['preview_img']))
                $categories[$key]['preview_img'] = $this->createPublicImgPath(unserialize($category['preview_img']));

            if(isset($category['properties']))
                $categories[$key]['properties'] = $this->handlePropertyForPublic($category['properties']);
        }

        return $categories;
    }
}
