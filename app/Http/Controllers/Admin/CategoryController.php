<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\ImgHelper;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $filterActive = "";
        $sort = "";
        $parentCategory = "";
        $searchText = "";

        $parentCategory = $request->cookie('parentCategory');
        $filterActive = $request->cookie('filterActiveCategory');
        $sort = $request->cookie('sortCategory');
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
            $this->deleteImg($request, $category);
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
        $obImage = $category->select(['id', 'preview_img'])->where('id', $category->id)->get();
        $arImage = unserialize($obImage->pluck('preview_img')[0]);

        if ($arImage) {
            foreach ($arImage as $key => $image) {
                $imgPath = public_path('images/shares/previews/' . $image);
                ImgHelper::deleteImg($imgPath);
            }
        }

        // Delete category
        Category::destroy($category->id);
        $request->session()->flash('success', 'Категория удалена');
        return redirect()->route('admin.category.index');
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
            $cookieJar->queue($cookieJar->forget('sortCategory'));
            $cookieJar->queue($cookieJar->forget('parentCategory'));
            $cookieJar->queue($cookieJar->forget('filterActiveCategory'));
        }
        else {
            // Filter by parent category
            if($request->categorySelect > 0)
                $cookieJar->queue(cookie('parentCategory', $request->categorySelect, 60));
            elseif($request->categorySelect == 0)
                $cookieJar->queue($cookieJar->forget('parentCategory'));

            // Filter by activity
            if($request->activeSelect && $request->activeSelect != "all")
                $cookieJar->queue(cookie('filterActiveCategory', $request->activeSelect, 60));
            elseif($request->activeSelect == "all")
                $cookieJar->queue($cookieJar->forget('filterActiveCategory'));

            // Sort
            if($request->sort && $request->sort != "default")
                $cookieJar->queue(cookie('sortCategory', $request->sort, 60));
            elseif($request->sort == "default")
                $cookieJar->queue($cookieJar->forget('sortCategory'));
        }

        return redirect()->route('admin.category.index');
    }

    /**
     * Delete preview image
     *
     * @param Request $request
     * @param Category $category
     */
    public function deleteImg(Request $request, Category $category)
    {
        $obImage = $category->select(['id', 'preview_img'])->where('id', $category->id)->get();
        $arImage = unserialize($obImage->pluck('preview_img')[0]);

        foreach ($arImage as $key => $image) {
            if ($image == $request->deleteImg) {
                unset($arImage[$key]);

                // Delete image on server
                $imgPath = public_path('images/shares/previews/' . $request->deleteImg);
                ImgHelper::deleteImg($imgPath);
            }
        }

        // Delete image from database
        $arImage = serialize($arImage);
        $category->where('id', $category->id)->update(['preview_img' => $arImage]);
    }
}
