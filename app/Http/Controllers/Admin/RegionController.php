<?php

namespace App\Http\Controllers\Admin;

use App\Region;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RegionController extends Controller
{
    use \App\FilterController;
    use \App\SearchController;

    public $indexRoute = 'admin.region.index';
    public $prefix = 'Region';

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

        $regions = new Region();

        // Filter
        $regions = $this->filterExec($request, $regions);

        // Search
        $regions = $this->searchByTitle($request, $regions);

        $regions = $regions->paginate(20);

        return view('admin.regions.index', [
            'regions' => $regions,
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

        return view('admin.regions.create', [
            'region' => [],
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
        $region = Region::create($request->all());

        $request->session()->flash('success', 'Регион добавлен');
        return redirect()->route('admin.region.edit', $region);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function show(Region $region)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function edit(Region $region)
    {
        if (Auth::user()->is_admin == 0)
            return redirect()->route('admin.index');

        return view('admin.regions.edit', [
            'region' => $region,
            'delimiter' => '-'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Region $region)
    {
        if ($request->delete)
            return $this->destroy($request, $region);

        $region->update($request->all());

        $request->session()->flash('success', 'Регион изменен');

        if ($request->saveFromList)
            return redirect()->route('admin.region.index');

        return redirect()->route('admin.region.edit', $region);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param \App\Region $region
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Region $region)
    {
        Region::destroy($region->id);

        $request->session()->flash('success', 'Регион удален');
        return redirect()->route('admin.region.index');
    }
}
