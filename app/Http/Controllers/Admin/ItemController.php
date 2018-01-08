<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    use \App\FilterController;
    use \App\SearchController;
    use \App\PropEnumController;
    use \App\ImgController;
    use \App\FileController;
    use \App\HandlePropertyController;
    use \App\CategoryTrait;

    public $indexRoute = 'admin.item.index';
    public $prefix = 'Item';

    /**
     * Display a listing of the resource
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $items = new Item();

        if (Auth::user()->is_admin == 0)
            $items = $items->where('created_by', Auth::user()->id);

        // Filter
        $items = $this->filterExec($request, $items);

        // Search
        $items = $this->searchByTitle($request, $items);

        $items = $items->paginate(20);

        return view('admin.items.index', [
            'items' => $items,
            'categories' => Category::orderby('title', 'asc')->select(['id', 'title'])->get(),
            'searchText' => $this->searchText,
            'filter' => $this->arFilter,
            'sort' => $this->sortVal,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = new Item();

        // Get properties
        $this->getProperties($item,PROP_KIND_ITEM);

        return view('admin.items.create', [
            'item' => [],
            'categories' => Category::with('children')->where('parent_id', '0')->get(),
            'items' => Item::select(['id', 'title'])->get()->toArray(),
            'delimiter' => '',
            'user' => Auth::user(),
            'propGroups' => $this->arProps
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
        $requestData = $request->all();

        // Set properties array
        if (isset($requestData['properties']))
            $requestData['properties'] = $this->setProperties($requestData['properties']);

        $item = Item::create($requestData);

        $request->session()->flash('success', 'Материал добавлен');
        return redirect()->route('admin.item.edit', $item);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        if (Auth::user()->is_admin == 0 && $item->created_by != Auth::user()->id)
            return redirect()->route('admin.item.index');

        $preview_images = unserialize($item->preview_img);

        // Get properties
        $this->getProperties($item,PROP_KIND_ITEM, $item->properties, $item->category_id);

        return view('admin.items.edit', [
            'item' => $item,
            'categories' => Category::with('children')->where('parent_id', '0')->get(),
            'items' => Item::select(['id', 'title'])->get()->toArray(),
            'delimiter' => '-',
            'user' => Auth::user(),
            'preview_images' => $preview_images,
            'propGroups' => $this->arProps
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        // Delete item
        if ($request->delete)
            return $this->destroy($request, $item);

        // Copy item
        if ($request->copy) {
            $request->preview_img = NULL;
            return  $this->store($request);
        }

        // Delete preview images
        if ($request->deleteImg) {
            $this->deleteMultiplePrevImg($request, $item, 'preview_img');
            return redirect()->route('admin.item.edit', $item);
        }

        // Delete property images
        if ($request->deletePropImg) {
            $this->deleteMultiplePropImg($request, $item);
            return redirect()->route('admin.item.edit', $item);
        }

        // Delete property files
        if ($request->deletePropFile) {
            $this->deletePropFile($request->deletePropFile, $item);
            return redirect()->route('admin.item.edit', $item);
        }

        $requestData = $request->all();

        // Set properties array
        if (isset($requestData['properties']))
            $requestData['properties'] = $this->setProperties($requestData['properties'], $item, $item->id);

        $item->update($requestData);

        $request->session()->flash('success', 'Материал отредактирован');

        if ($request->saveFromList)
            return redirect()->route('admin.item.index');

        return redirect()->route('admin.item.edit', $item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Item $item)
    {
        $this->itemDestroy($item);

        $request->session()->flash('success', 'Материал удален');
        return redirect()->route('admin.item.index');
    }
}
