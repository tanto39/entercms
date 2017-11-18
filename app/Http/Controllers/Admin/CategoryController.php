<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\ImgHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    use \App\FilterController;
    use \App\SearchController;
    use \App\ImgController;

    public $indexRoute = 'admin.category.index';
    public $prefix = 'Category';

    /**
     * Display a listing of the resource
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $categories = new Category();

        // Filter
        $categories = $this->filterExec($request, $categories);

        // Search
        $categories = $this->searchByTitle($request, $categories);

        $categories = $categories->paginate(20);

        return view('admin.categories.index', [
            'categories' => $categories,
            'parents' => Category::orderby('title', 'asc')->select(['id', 'title'])->get(),
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
        return view('admin.categories.create', [
            'category' => [],
            'categories' => Category::with('children')->where('parent_id', '0')->get(),
            'delimiter' => '',
            'user' => Auth::user()
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
        $category = Category::create($request->all());

        $request->session()->flash('success', 'Категория добавлена');
        return redirect()->route('admin.category.edit', $category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $preview_images = unserialize($category->preview_img);

        return view('admin.categories.edit', [
            'category' => $category,
            'categories' => Category::with('children')->where('parent_id', '0')->get(),
            'delimiter' => '-',
            'user' => Auth::user(),
            'preview_images' => $preview_images
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        // Delete category
        if ($request->delete)
            return $this->destroy($request, $category);

        // Copy category
        if ($request->copy) {
            $request->preview_img = NULL;
            return  $this->store($request);
        }

        // Delete preview images
        if ($request->deleteImg) {
            $this->deleteMultipleImg($request, $category, 'preview_img', 'images/shares/previews/');
            return redirect()->route('admin.category.edit', $category);
        }

        $category->update($request->all());

        $request->session()->flash('success', 'Категория отредактирована');

        if ($request->saveFromList)
            return redirect()->route('admin.category.index');

        return redirect()->route('admin.category.edit', $category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Category $category)
    {
        // Delete preview images
        $this->deleteImgWithDestroy($category, 'preview_img', 'images/shares/previews/');

        // Delete category
        Category::destroy($category->id);
        $request->session()->flash('success', 'Категория удалена');
        return redirect()->route('admin.category.index');
    }
}
