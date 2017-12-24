<?php

namespace App\Http\Controllers\Site;

use App;
use App\Item;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
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

        if (isset($category[0])) {
            $items = $this->getItems($category[0], 0);

            return view('public/categories/category', [
                'result' => $category[0],
                'items' => $items
            ]);
        }
        else
            App::abort(404);
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
        if (isset($category[0])) {
            $items = $this->getItems($category[0], 1);

            return view('public/catalog/category', [
                'result' => $category[0],
                'items' => $items
            ]);
        }
        else
            App::abort(404);
    }

    /**
     * Show blog categories
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showBlogCategories()
    {
        $categories = $this->getCategories(0);

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

        return view('public/catalog/categories', [
            'result' => $categories,
        ]);
    }

    /**
     * Get categories
     *
     * @param $isCatalog
     * @return mixed
     */
    public function getCategories($isCatalog)
    {
        if ($isCatalog === 1)
            $parentId = CATALOG_ID;
        else
            $parentId = BLOG_ID;

        $categories = Category::where('catalog_section', $isCatalog)
            ->where('parent_id', $parentId)
            ->select([
                'title',
                'preview_img',
                'slug',
                'description'
            ])
            ->get();

        return $categories;
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
            ->get();

        return $category;
    }

    /**
     * Get items
     *
     * @param $category
     * @param $isProduct
     * @return mixed
     */
    public function getItems($category, $isProduct)
    {
        $items = new Item();

        $items = $items->where('category_id', $category->id)
            ->where('published', 1)->where('is_product', $isProduct)
            ->select([
                'title',
                'preview_img',
                'rating',
                'slug',
                'description'
            ]);

        // TODO sort and filter
        $items = $items->paginate(20);

        return $items;
    }
}
