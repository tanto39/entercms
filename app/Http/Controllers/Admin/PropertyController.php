<?php

namespace App\Http\Controllers\Admin;

use App\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Cookie\CookieJar;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $sort = "";
        $searchText = "";

        $sort = $request->cookie('sortProperty');
        $searchText = $request->get('searchText');

        // Sort
        switch ($sort) {
            case "dateUp":
                $properties = Property::orderby('updated_at', 'desc');
                break;

            case "dateDown":
                $properties = Property::orderby('updated_at', 'asc');
                break;

            case "title":
                $properties = Property::orderby('title', 'asc');
                break;

            default:
                $properties = Property::orderby('order', 'asc')->orderby('updated_at', 'desc');
        }

        // Search
        if(!empty($searchText))
            $properties = $properties->where('title', 'like', "%{$searchText}%");

        $properties = $properties->paginate(20);

        return view('admin.properties.index', [
            'properties' => $properties,
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
        return view('admin.properties.create', [
            'property' => [],
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
        $property = Property::create($request->all());

        $request->session()->flash('success', 'Свойство добавлено');
        return redirect()->route('admin.property.edit', $property);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function show(Property $property)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function edit(Property $property)
    {
        return view('admin.properties.edit', [
            'property' => $property,
            'delimiter' => '-'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Property $property)
    {
        if ($request->delete)
            return $this->destroy($request, $property);

        $property->update($request->all());

        $request->session()->flash('success', 'Свойство отредактировано');

        if ($request->saveFromList)
            return redirect()->route('admin.property.index');

        return redirect()->route('admin.property.edit', $property);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Property $property
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Property $property)
    {
        Property::destroy($property->id);
        $request->session()->flash('success', 'Свойство удалено');
        return redirect()->route('admin.property.index');
    }

    /**
     * Set cookie for property filter and sort
     *
     * @param CookieJar $cookieJar
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function filter(CookieJar $cookieJar, Request $request)
    {
        if($request->reset) {
            $cookieJar->queue($cookieJar->forget('sortProperty'));
        }
        else {
            // Sort
            if($request->sort && $request->sort != "default")
                $cookieJar->queue(cookie('sortProperty', $request->sort, 60));
            elseif($request->sort == "default")
                $cookieJar->queue($cookieJar->forget('sortProperty'));
        }

        return redirect()->route('admin.property.index');
    }
}
