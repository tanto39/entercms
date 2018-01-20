<?php

namespace App\Http\Controllers\Site;

use App;
use App\Item;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    use \App\ImgController;
    use \App\PropEnumController;
    use \App\HandlePropertyController;
    use \App\CategoryTrait;
    use \App\FilterController;

    public $indexRoute = 'category.index';
    public $prefix = 'Category';

    /**
     * Show blog category
     *
     * @param $category_slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showBlogCategory($category_slug, Request $request)
    {
        // Get category
        $category = $this->getCategory($category_slug, 0);

        $category = $this->handleCategoryArray($category);

        if (!empty($category['children']))
            foreach ($category['children'] as $key=>$child)
                $category['children'][$key] = $this->handleCategoryArray($child);

        // Items
        $items = $this->getItems($category, 0, $request);
        $itemsLink = $items->links();

        $items = $this->handleItemsArray($items);

        return view('public/categories/category', [
            'result' => $category,
            'items' => $items,
            'itemsLink' => $itemsLink
        ]);
    }

    /**
     * Show catalog category
     *
     * @param $category_slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showCatalogCategory($category_slug, Request $request)
    {
        // Redirect if unset smart filter
        $unsetFilter = $request->get('unsetfilter');

        if (isset($unsetFilter)) {
            $requestUri = url()->getRequest()->path();
            return redirect($requestUri);
        }

        // Get category
        $category = $this->getCategory($category_slug, 1);

        // Get item with reviews and categories

        $category = $this->handleCategoryArray($category);

        if (!empty($category['children']))
            foreach ($category['children'] as $key=>$child)
                $category['children'][$key] = $this->handleCategoryArray($child);

        // Filter properties
        $properties = $this->getFilterProperties($request);

        // Items
        $items = $this->getItems($category, 1, $request);
        $itemsLink = $items->links();

        $items = $this->handleItemsArray($items);

        // View
        return view('public/catalog/category', [
            'result' => $category,
            'items' => $items,
            'properties' => $properties,
            'itemsLink' => $itemsLink
        ]);

    }
    /**
     * Show blog categories
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showBlogCategories()
    {
        // Get category
        $category = $this->getCategory(BLOG_SLUG, 0);
        $category = $this->handleCategoryArray($category);

        if (!empty($category['children']))
            foreach ($category['children'] as $key=>$child)
                $category['children'][$key] = $this->handleCategoryArray($child);

        return view('public/categories/categories', [
            'result' => $category,
        ]);
    }

    /**
     * Show catalog main page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showCatalogCategories()
    {
        // Get category
        $category = $this->getCategory(CATALOG_SLUG, 1);
        $category = $this->handleCategoryArray($category);

        if (!empty($category['children']))
            foreach ($category['children'] as $key=>$child)
                $category['children'][$key] = $this->handleCategoryArray($child);

        return view('public/catalog/categories', [
            'result' => $category
        ]);
    }

}
