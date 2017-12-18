<?php

namespace App\Http\Controllers\Admin;

use App\Menu;
use App\MenuItem;
use App\MenuTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    use \App\FilterController;
    use \App\SearchController;
    use \App\MenuTrait;

    public $indexRoute = 'admin.menu.index';
    public $prefix = 'Menu';

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

        $menus = new Menu();

        // Filter
        $menus = $this->filterExec($request, $menus);

        // Search
        $menus = $this->searchByTitle($request, $menus);

        $menus = $menus->paginate(20);

        return view('admin.menus.index', [
            'menus' => $menus,
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

        return view('admin.menus.create', [
            'menu' => [],
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
        $menu = Menu::create($request->all());

        $request->session()->flash('success', 'Меню добавлено');
        return redirect()->route('admin.menu.edit', $menu);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        if (Auth::user()->is_admin == 0)
            return redirect()->route('admin.index');

        return view('admin.menus.edit', [
            'menu' => $menu,
            'delimiter' => '-'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        if ($request->delete)
            return $this->destroy($request, $menu);

        $menu->update($request->all());

        $request->session()->flash('success', 'Меню изменено');

        if ($request->saveFromList)
            return redirect()->route('admin.menu.index');

        return redirect()->route('admin.menu.edit', $menu);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Menu $menu
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Menu $menu)
    {
        $this->deleteMenuItem('menu', $menu->id);
        Menu::destroy($menu->id);

        $request->session()->flash('success', 'Меню удалено');
        return redirect()->route('admin.menu.index');
    }
}
