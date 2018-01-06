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

        if (isset($category)) {

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
        if (isset($category)) {

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
                'order',
                'preview_img',
                'slug',
                'description'
            ])
            ->orderby('order', 'asc')->orderby('updated_at', 'desc')
            ->get()->toArray();


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
            ->get()->toArray()[0];

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

        $items = $items->where('category_id', $category['id'])
            ->where('published', 1)->where('is_product', $isProduct)
            ->select([
                'title',
                'preview_img',
                'order',
                'rating',
                'slug',
                'description',
                'properties'
            ])
            ->orderby('order', 'asc')->orderby('updated_at', 'desc');

        // TODO sort and filter
        $items = $items->paginate(20);

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
        $items = $items->toArray()['data'];

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
