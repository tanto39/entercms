<?php

namespace App\Http\Controllers\Admin;

use App\Review;
use App\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    use \App\FilterController;
    use \App\SearchController;

    public $indexRoute = 'admin.review.index';
    public $prefix = 'Review';

    /**
     * Display a listing of the resource
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $reviews = new Review();

        // Filter
        $reviews = $this->filterExec($request, $reviews);

        // Search
        $reviews = $this->searchByTitle($request, $reviews);

        $reviews = $reviews->paginate(20);

        return view('admin.reviews.index', [
            'reviews' => $reviews,
            'items' => Item::orderby('title', 'asc')->select(['id', 'title'])->get(),
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
        return view('admin.reviews.create', [
            'items' => Item::orderby('title', 'asc')->select(['id', 'title'])->get(),
            'reviews' => Review::orderby('title', 'asc')->select(['id', 'title'])->get(),
            'review' => [],
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
        $review = Review::create($request->all());

        $request->session()->flash('success', 'Отзыв добавлен');
        return redirect()->route('admin.review.edit', $review);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        return view('admin.reviews.edit', [
            'items' => Item::orderby('title', 'asc')->select(['id', 'title'])->get(),
            'reviews' => Review::orderby('title', 'asc')->select(['id', 'title'])->get(),
            'review' => $review,
            'delimiter' => '-'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        if ($request->delete)
            return $this->destroy($request, $review);

        $review->update($request->all());

        $request->session()->flash('success', 'Отзыв отредактирован');

        if ($request->saveFromList)
            return redirect()->route('admin.review.index');

        return redirect()->route('admin.review.edit', $review);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param review $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Review $review)
    {
        Review::destroy($review->id);
        $request->session()->flash('success', 'Отзыв удален');
        return redirect()->route('admin.review.index');
    }
}
