<?php

namespace App\Http\Controllers\Site;

use App;
use Illuminate\Http\Request;
use App\Item;
use App\Category;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    use \App\ImgController;
    use \App\PropEnumController;
    use \App\HandlePropertyController;
    use \App\CategoryTrait;
    use \App\SearchController;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get items
        $items = new Item();
        $searchText = $request->get('searchText');

        if (empty($searchText)) {
            $request->session()->flash('success', 'Введен пустой поисковый запрос.');
            return redirect()->back();
        }

        $items = $this->searchByTitle($request, $items);

        $items = $items->with('category')->orderby('order', 'asc')->orderby('updated_at', 'desc')->paginate(20);
        $items->setPath('search?searchText=' . $searchText);

        $itemsLink = $items->links();

        $items = $this->handleItemsArray($items);

        // Set hrefs
        foreach ($items as $key=>$item) {
            $items[$key]['href'] = '#';

            if (!is_null($item['category'])) {
                if($item['is_product'] == 0)
                    $items[$key]['href'] = '/' . BLOG_SLUG . '/' . $item['category']['slug'] . '/' . $item['slug'];
                else
                    $items[$key]['href'] = '/' . CATALOG_SLUG . '/' . $item['category']['slug'] . '/' . $item['slug'];
            }
            else {
                $items[$key]['href'] = '/' . $item['slug'];
            }
        }

        return view('public/search/search', [
            'result' => $items,
            'itemsLink' => $itemsLink,
            'searchText' => $searchText
        ]);

    }
}
