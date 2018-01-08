<?php

namespace App\Http\Controllers\Admin;

use App\Menu;
use App\MenuItem;
use App\Item;
use App\Category;
use App\MenuType;
use App\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Object_;

class MenuItemController extends Controller
{
    use \App\FilterController;
    use \App\SearchController;
    use \App\MenuTrait;

    public $indexRoute = 'admin.menuitem.index';
    public $prefix = 'Menuitem';

    /**
     * Display a listing of the resource
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if (Auth::user()->is_admin == 0)
            return redirect()->route('admin.index');

        $menuitems = new MenuItem();

        // Filter
        $menuitems = $this->filterExec($request, $menuitems);

        // Search
        $menuitems = $this->searchByTitle($request, $menuitems);

        $menuitems = $menuitems->paginate(20);

        return view('admin.menuitems.index', [
            'menuitems' => $menuitems,
            'menus' => Menu::orderby('title', 'asc')->select(['id', 'title'])->get(),
            'menutypes' => MenuType::orderby('title', 'asc')->select(['id', 'title'])->get(),
            'searchText' => $this->searchText,
            'filter' => $this->arFilter,
            'sort' => $this->sortVal
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->is_admin == 0)
            return redirect()->route('admin.index');

        return view('admin.menuitems.create', [
            'menuitem' => [],
            'parentItems' => MenuItem::orderby('title', 'asc')->select(['id', 'title'])->get(),
            'menus' => Menu::orderby('title', 'asc')->select(['id', 'title'])->get(),
            'menutypes' => MenuType::orderby('title', 'asc')->select(['id', 'title'])->get(),
            'delimiter' => ''
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $menuitem = MenuItem::create($request->all());

        $request->session()->flash('success', 'Пункт меню добавлен');
        return redirect()->route('admin.menuitem.edit', $menuitem);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MenuItem  $menuitem
     * @return \Illuminate\Http\Response
     */
    public function show(MenuItem $menuitem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MenuItem  $menuitem
     * @return \Illuminate\Http\Response
     */
    public function edit(MenuItem $menuitem)
    {
        $linkItems = [];

        if (Auth::user()->is_admin == 0)
            return redirect()->route('admin.index');

        if ($menuitem->type == MENU_TYPE_ITEM)
            $linkItems = Item::orderby('title', 'asc')->select(['id', 'title'])->get();
        elseif($menuitem->type == MENU_TYPE_CATEGORY)
            $linkItems = Category::orderby('title', 'asc')->select(['id', 'title'])->get();

        return view('admin.menuitems.edit', [
            'menuitem' => $menuitem,
            'parentItems' => MenuItem::orderby('title', 'asc')->select(['id', 'title'])->get(),
            'menus' => Menu::orderby('title', 'asc')->select(['id', 'title'])->get(),
            'menutypes' => MenuType::orderby('title', 'asc')->select(['id', 'title'])->get(),
            'linkItems' => $linkItems,
            'delimiter' => '-'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MenuItem  $menuitem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MenuItem $menuitem)
    {
        if ($request->delete)
            return $this->destroy($request, $menuitem);

        $requestData = $this->getRequestData($request);

        $menuitem->update($requestData);

        $request->session()->flash('success', 'Пункт меню изменен');

        if ($request->saveFromList)
            return redirect()->route('admin.menuitem.index');

        return redirect()->route('admin.menuitem.edit', $menuitem);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param MenuItem $menuitem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, MenuItem $menuitem)
    {
        MenuItem::destroy($menuitem->id);
        $request->session()->flash('success', 'Пункт меню удален');
        return redirect()->route('admin.menuitem.index');
    }

    /**
     * Get request and check
     *
     * @param Request $request
     * @return array
     */
    public function getRequestData(Request $request)
    {
        $requestData = $request->all();

        if (isset($requestData['type']))
            $requestData['href'] = $this->generateHref($requestData);

        return $requestData;
    }
}
