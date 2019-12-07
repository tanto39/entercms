<?php

namespace App\Http\Controllers\Site;

use App;
use App\Item;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;

class CategoryController extends Controller
{
    use \App\ImgController;
    use \App\PropEnumController;
    use \App\HandlePropertyController;
    use \App\CategoryTrait;
    use \App\FilterController;
    use \App\SortTrait;

    public $indexRoute = 'item.showCatalogCategory';
    public $prefix = 'CategoryPublic';

    /**
     * Show blog category
     *
     * @param $category_slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showBlogCategory($category_slug, Request $request)
    {
        $template = TemplateController::getInstance();
        if($template->isInstance == 'N') $template->setTemplateVariables();

        // Get category
        $category = $this->getCategory($category_slug, 0);

        $category = $this->handleCategoryArray($category);

        if (!empty($category['children']))
            foreach ($category['children'] as $key=>$child)
                $category['children'][$key] = $this->handleCategoryArray($child);

        // Items
        $items = $this->getItems($category, 0, $request);

        $requestUri = $request->getRequestUri();
        $items = $items->paginate(PAGE_COUNT);
        $items->setPath($requestUri);
        $itemsLink = $items->links();

        $items = $items->toArray();
        $items = $this->handleItemsArray($items['data']);

        return view('public/categories/category', [
            'result' => $category,
            'items' => $items,
            'itemsLink' => $itemsLink,
            'template' => $template
        ]);
    }

    /**
     * Show catalog category
     *
     * @param Request $request
     * @param $category_slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showCatalogCategory($category_slug, Request $request)
    {
        // Redirect if unset smart filter
        $unsetFilter = $request->get('unsetfilter');

        $template = TemplateController::getInstance();
        if($template->isInstance == 'N') $template->setTemplateVariables();

        if (isset($unsetFilter)) {
            return redirect($template->uri);
        }

        // Get category
        $category = $this->getCategory($category_slug, 1);

        // Get item with reviews and categories

        $category = $this->handleCategoryArray($category);

        if (!empty($category['children']))
            foreach ($category['children'] as $key=>$child)
                $category['children'][$key] = $this->handleCategoryArray($child);

        // Filter properties
        $properties = $this->getFilterProperties($request, $category['id']);

        // Get sort properties
        $sortProps = $this->getAllSortProperties();

        // Items
        $items = $this->getItems($category, 1, $request);

        $items = $this->handleItemsArray($items);

        // Set sort property
        $arSortProp = [0=>'', 1=>'', 2=>''];
        if ($request->get('setsort'.$this->prefix)) {
            $arSortProp = explode("_", $request->get('sort' . $this->prefix));
            if ($arSortProp[1] != 'default')
                $items = $this->setSortByProps($items, $arSortProp, $request);
        }

        $itemsLink = $this->arrayPaginate($request, $items);

        // Get items for current page
        $items = $this->getCurrentPageItems($request, $items);

        // View
        return view('public/catalog/category', [
            'result' => $category,
            'items' => $items,
            'properties' => $properties,
            'sortProps' => $sortProps,
            'arSortProp' => $arSortProp,
            'itemsLink' => $itemsLink,
            'template' => $template
        ]);

    }
    /**
     * Show blog categories
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showBlogCategories()
    {
        $template = TemplateController::getInstance();
        if($template->isInstance == 'N') $template->setTemplateVariables();

        // Get category
        $category = $this->getCategory(BLOG_SLUG, 0);
        $category = $this->handleCategoryArray($category);

        if (!empty($category['children']))
            foreach ($category['children'] as $key=>$child)
                $category['children'][$key] = $this->handleCategoryArray($child);

        return view('public/categories/categories', [
            'result' => $category,
            'template' => $template
        ]);
    }

    /**
     * Show catalog main page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showCatalogCategories()
    {
        $template = TemplateController::getInstance();
        if($template->isInstance == 'N') $template->setTemplateVariables();

        // Get category
        $category = $this->getCategory(CATALOG_SLUG, 1);
        $category = $this->handleCategoryArray($category);

        if (!empty($category['children']))
            foreach ($category['children'] as $key=>$child)
                $category['children'][$key] = $this->handleCategoryArray($child);

        return view('public/catalog/categories', [
            'result' => $category,
            'template' => $template
        ]);
    }

}
