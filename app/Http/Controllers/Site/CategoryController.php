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
        $category = new Category();
        $items = new Item();

        // Get item with reviews and categories
        $category = $category->with('children')
            ->where('slug', $category_slug)
            ->where('catalog_section', 0)
            ->get();

        if (isset($category[0])) {
            $items = $items->where('category_id', $category[0]->id)
                ->where('published', 1)->where('is_product', 0)
                ->select([
                    'title',
                    'preview_img',
                    'rating',
                    'slug',
                    'description'
                ]);

            // TODO item filter
            $items = $items->paginate(20);

            return view('public/categories/category', [
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
        $categories = Category::where('catalog_section', 0)
            ->where('parent_id', 0)
            ->select([
                'title',
                'preview_img',
                'slug',
                'description'
            ])
            ->get();

        return view('public/categories/categories', [
            'result' => $categories,
        ]);
    }
}
