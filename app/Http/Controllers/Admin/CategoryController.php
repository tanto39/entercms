<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filterActive = "";
        $sort = "";
        $parentCategory = "";
        $searchText = "";

        $parentCategory = $request->cookie('parentCategory');
        $filterActive = $request->cookie('filterActive');
        $sort = $request->cookie('sort');
        $searchText = $request->get('searchText');

        // Sort
        switch ($sort) {
            case "dateUp":
            $categories = Category::orderby('updated_at', 'desc');
            break;

            case "dateDown":
            $categories = Category::orderby('updated_at', 'asc');
            break;

            case "title":
            $categories = Category::orderby('title', 'asc');
            break;

            default:
            $categories = Category::orderby('order', 'asc')->orderby('updated_at', 'desc');
        }

        // Search
        if(!empty($searchText))
            $categories = $categories->where('title', 'like', "%{$searchText}%");

        // Filter by parent category
        if($parentCategory)
            $categories = $categories->where('parent_id', $parentCategory);

        // Filter by activity
        if($filterActive == 'Y')
            $categories = $categories->where('published', 1);
        elseif($filterActive == 'N')
            $categories = $categories->where('published', 0);

        $categories = $categories->paginate(20);

        return view('admin.categories.index', [
            'categories' => $categories,
            'parents' => Category::orderby('title', 'asc')->select(['id', 'title'])->get(),
            'filterCategory' => $parentCategory,
            'searchText' => $searchText,
            'filterActive' => $filterActive,
            'sort' => $sort
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
        return view('admin.categories.edit', [
            'category' => $category,
            'categories' => Category::with('children')->where('parent_id', '0')->get(),
            'delimiter' => '-',
            'user' => Auth::user()
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
        if ($request->delete) {
            $this->destroy($request, $category);
            return redirect()->route('admin.category.index');
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
        Category::destroy($category->id);
        $request->session()->flash('success', 'Категория удалена');
    }

    /**
     * Set cookie for category filter and sort
     *
     * @param CookieJar $cookieJar
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function filter(CookieJar $cookieJar, Request $request)
    {
        if($request->reset) {
            $cookieJar->queue($cookieJar->forget('sort'));
            $cookieJar->queue($cookieJar->forget('parentCategory'));
            $cookieJar->queue($cookieJar->forget('filterActive'));
        }
        else {
            // Filter by parent category
            if($request->categorySelect > 0)
                $cookieJar->queue(cookie('parentCategory', $request->categorySelect, 60));
            elseif($request->categorySelect == 0)
                $cookieJar->queue($cookieJar->forget('parentCategory'));

            // Filter by activity
            if($request->activeSelect && $request->activeSelect != "all")
                $cookieJar->queue(cookie('filterActive', $request->activeSelect, 60));
            elseif($request->activeSelect == "all")
                $cookieJar->queue($cookieJar->forget('filterActive'));

            // Sort
            if($request->sort && $request->sort != "default")
                $cookieJar->queue(cookie('sort', $request->sort, 60));
            elseif($request->sort == "default")
                $cookieJar->queue($cookieJar->forget('sort'));
        }

        return redirect()->route('admin.category.index');
    }
}
