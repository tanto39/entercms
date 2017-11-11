<?php

namespace App\Http\Controllers\Admin;

use App\PropGroup;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PropGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort = "";
        $searchText = "";

        $sort = $request->cookie('sortPropGroup');
        $searchText = $request->get('searchText');

        // Sort
        switch ($sort) {
            case "dateUp":
                $propgroups = PropGroup::orderby('updated_at', 'desc');
                break;

            case "dateDown":
                $propgroups = PropGroup::orderby('updated_at', 'asc');
                break;

            case "title":
                $propgroups = PropGroup::orderby('title', 'asc');
                break;

            default:
                $propgroups = PropGroup::orderby('order', 'asc')->orderby('updated_at', 'desc');
        }

        // Search
        if(!empty($searchText))
            $propgroups = $propgroups->where('title', 'like', "%{$searchText}%");

        $propgroups = $propgroups->paginate(20);

        return view('admin.propgroups.index', [
            'propgroups' => $propgroups,
            'searchText' => $searchText,
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
        return view('admin.propgroups.create', [
            'propgroup' => [],
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
        $propgroup = PropGroup::create($request->all());

        $request->session()->flash('success', 'Группа свойств добавлена');
        return redirect()->route('admin.propgroup.edit', $propgroup);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PropGroup  $propgroup
     * @return \Illuminate\Http\Response
     */
    public function show(PropGroup $propgroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PropGroup  $propgroup
     * @return \Illuminate\Http\Response
     */
    public function edit(PropGroup $propgroup)
    {
        return view('admin.propgroups.edit', [
            'propgroup' => $propgroup,
            'delimiter' => '-'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PropGroup  $propgroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PropGroup $propgroup)
    {
        if ($request->delete) {
            $this->destroy($request, $propgroup);
            return redirect()->route('admin.propgroup.index');
        }

        $propgroup->update($request->all());

        $request->session()->flash('success', 'Группа отредактирована');

        if ($request->saveFromList)
            return redirect()->route('admin.propgroup.index');

        return redirect()->route('admin.propgroup.edit', $propgroup);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PropGroup  $propgroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, PropGroup $propgroup)
    {
        PropGroup::destroy($propgroup->id);
        $request->session()->flash('success', 'Группа удалена');
    }

    /**
     * Set cookie for property group filter and sort
     *
     * @param CookieJar $cookieJar
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function filter(CookieJar $cookieJar, Request $request)
    {
        if($request->reset) {
            $cookieJar->queue($cookieJar->forget('sortPropGroup'));
        }
        else {
            // Sort
            if($request->sort && $request->sort != "default")
                $cookieJar->queue(cookie('sortPropGroup', $request->sort, 60));
            elseif($request->sort == "default")
                $cookieJar->queue($cookieJar->forget('sortPropGroup'));
        }

        return redirect()->route('admin.propgroup.index');
    }
}
