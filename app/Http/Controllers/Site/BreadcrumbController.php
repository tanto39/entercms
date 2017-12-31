<?php

namespace App\Http\Controllers\Site;

use App;
use App\Item;
use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class BreadcrumbController extends Controller
{
    public static $breadcrumbs = [];

    /**
     * Create breadcrumbs array
     *
     * @return array
     */
    public static function createBreadcrumbs()
    {
        $uri = url()->getRequest()->path();
        $routeName = Route::currentRouteName();

        $uriParts = explode('/', $uri);

        switch ($routeName) {
            case 'item.showBlogCategory':
                $category = self::selectCategory($uriParts[1], BLOG_SLUG, 0, 'Y');
                self::selectParentCategories($category, BLOG_SLUG, BLOG_ID, 0);
                self::setMainLink(BLOG_SLUG, BLOG_TITLE, 'N');
            break;

            case 'item.showCatalogCategory':
                $category = self::selectCategory($uriParts[1], CATALOG_SLUG, 1, 'Y');
                self::selectParentCategories($category, CATALOG_SLUG, CATALOG_ID, 1);
                self::setMainLink(CATALOG_SLUG, CATALOG_TITLE, 'N');
            break;

            case 'item.showBlogCategories':
                self::setMainLink(BLOG_SLUG, BLOG_TITLE, 'Y');
            break;

            case 'item.showCatalogCategories':
                self::setMainLink(CATALOG_SLUG, CATALOG_TITLE, 'Y');
            break;

            case 'item.showBlogItem':
                self::selectItem($uriParts[2], 0);
                $category = self::selectCategory($uriParts[1], BLOG_SLUG, 0, 'N');
                self::selectParentCategories($category, BLOG_SLUG, BLOG_ID, 0);
                self::setMainLink(BLOG_SLUG, BLOG_TITLE, 'N');
            break;

            case 'item.showProduct':
                self::selectItem($uriParts[2], 1);
                $category = self::selectCategory($uriParts[1], CATALOG_SLUG, 1, 'N');
                self::selectParentCategories($category, CATALOG_SLUG, CATALOG_ID, 1);
                self::setMainLink(CATALOG_SLUG, CATALOG_TITLE, 'N');
            break;

            case 'item.showUncaterorised':
                self::selectItem($uriParts[0]);
            break;

        }

        if ($uri != '/') {
            // Main page
            self::$breadcrumbs[] = [
                'title' => 'Главная',
                'href' => '/',
                'active' => 'N'
            ];

            // Revers items
            self::$breadcrumbs = array_reverse(self::$breadcrumbs);
        }

        return self::$breadcrumbs;
    }

    /**
     * Set breadcrumb item for blog link or catalog link
     *
     * @param string $mainSlug
     * @param string $title
     * @param string $active
     */
    public static function setMainLink($mainSlug = BLOG_SLUG, $title = BLOG_TITLE, $active = 'N')
    {
        self::$breadcrumbs[] = [
            'title' => $title,
            'href' => '/' . $mainSlug,
            'active' => $active
        ];
    }

    /**
     * Select item and add to breadcrumbs array
     *
     * @param $slug
     * @param int $isProduct
     */
    public static function selectItem($slug, $isProduct = 0)
    {
        $item = Item::where('slug', $slug)
            ->where('is_product', $isProduct)
            ->select(['id', 'slug', 'title'])
            ->get()
            ->toArray()[0];

        self::$breadcrumbs[] = [
            'title' => $item['title'],
            'href' => '/' . $item['slug'],
            'active' => "Y"
        ];
    }

    /**
     * Select category and add to breadcrumbs array
     *
     * @param $slug
     * @param int $isCatalog
     * @param string $active
     * @return mixed
     */
    public static function selectCategory($slug, $mainSlug = BLOG_SLUG, $isCatalog = 0, $active = 'Y')
    {
        $category = Category::where('slug', $slug)
            ->where('catalog_section', $isCatalog)
            ->select(['id', 'slug', 'title', 'parent_id'])
            ->get()
            ->toArray()[0];

        self::$breadcrumbs[] = [
            'title' => $category['title'],
            'href' => '/' . $mainSlug . '/' . $category['slug'],
            'active' => $active
        ];

        return $category;
    }

    /**
     * Select parent categories and add them to breadcrumbs array
     *
     * @param $category
     * @param $mainSlug
     * @param $mainId
     * @param int $isCatalog
     */
    public static function selectParentCategories($category, $mainSlug, $mainId, $isCatalog = 0)
    {
        if (isset($category['parent_id']) && ($category['parent_id'] !== 0)) {
            $parent = Category::where('id', $category['parent_id'])
                ->where('catalog_section', $isCatalog)
                ->select('id', 'title', 'slug', 'parent_id')
                ->get()
                ->toArray()[0];

            if ($parent['id'] !== $mainId) {
                self::$breadcrumbs[] = [
                    'title' => $parent['title'],
                    'href' => '/' . $mainSlug . '/' . $parent['slug'],
                    'active' => 'N'
                ];

                if (isset($parent['parent_id']) && ($parent['parent_id'] !== 0))
                    self::selectParentCategories($parent, $mainSlug, $mainId, $isCatalog);
            }
        }
    }
}
