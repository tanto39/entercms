<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Category;

class HomeController extends Controller
{
    use \App\ImgController;
    use \App\PropEnumController;
    use \App\HandlePropertyController;
    use \App\CategoryTrait;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get item
        $item = Item::where('slug', '/')->get()->toArray();

        if(isset($item[0])) {
            $item = $item[0];
        }
        else
            App::abort(404);

        if(isset($item['preview_img']))
            $item['preview_img'] = $this->createPublicImgPath(unserialize($item['preview_img']));

        if(isset($item['properties']))
            $item['properties'] = $this->handlePropertyForPublic($item['properties']);

        // Get catalog
        $categories = Category::where('catalog_section', 1)
            ->where('parent_id', CATALOG_ID)
            ->select([
                'title',
                'order',
                'preview_img',
                'slug',
                'description'
            ])
            ->orderby('order', 'asc')->orderby('updated_at', 'desc')
            ->skip(0)->take(3)
            ->get()->toArray();

        $categories = $this->handleCategoriesArray($categories);

        return view('public/home', [
            'result' => $item,
            'categories' => $categories
        ]);

    }
}
