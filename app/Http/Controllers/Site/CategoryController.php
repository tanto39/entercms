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

    public $indexRoute = 'category.index';
    public $prefix = 'Category';

    /**
     * Show blog category
     *
     * @param $category_slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showBlogCategory($category_slug)
    {
        // Get category
        $category = $this->getCategory($category_slug, 0);

        $category = $this->handleCategoryArray($category);

        // Items
        $items = $this->getItems($category, 0);
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
    public function showCatalogCategory($category_slug)
    {
        // Get category
        $category = $this->getCategory($category_slug, 1);

        // Get item with reviews and categories

        $category = $this->handleCategoryArray($category);

        // Items
        $items = $this->getItems($category, 1);
        $itemsLink = $items->links();

        $items = $this->handleItemsArray($items);

        // View
        return view('public/catalog/category', [
            'result' => $category,
            'items' => $items,
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
        $categories = $this->getCategories(0);
        $categories = $this->handleCategoriesArray($categories);

        return view('public/categories/categories', [
            'result' => $categories,
        ]);
    }

    /**
     * Show catalog main page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showCatalogCategories()
    {
        $categories = $this->getCategories(1);
        $categories = $this->handleCategoriesArray($categories);

        return view('public/catalog/categories', [
            'result' => $categories,
        ]);
    }

}
