<?php

namespace App\Http\Controllers\Admin;

use App\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Cookie\CookieJar;
use App\Category;
use App\PropKind;
use App\PropGroup;
use App\PropType;

class PropertyController extends Controller
{
    use \App\FilterController;

    public $categories = [];
    public $propKinds = [];
    public $propGroups = [];
    public $propTypes = [];

    public $indexRoute = 'admin.property.index';
    public $prefix = 'Property';

    /**
     * Display a listing of the resource
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->getSelectForForm();

        $searchText = $request->get('searchText');

        $properties = new Property();
        $properties = $this->filterExec($request, $properties);

        // Search
        if(!empty($searchText))
            $properties = $properties->where('title', 'like', "%{$searchText}%");

        $properties = $properties->paginate(20);

        return view('admin.properties.index', [
            'properties' => $properties,
            'searchText' => $searchText,
            'categories' => $this->categories,
            'propKinds' => $this->propKinds,
            'propGroups' => $this->propGroups,
            'propTypes' => $this->propTypes,
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
        $this->getSelectForForm();

        return view('admin.properties.create', [
            'property' => [],
            'delimiter' => '',
            'categories' => $this->categories,
            'propKinds' => $this->propKinds,
            'propGroups' => $this->propGroups,
            'propTypes' => $this->propTypes,
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
        $requestData = $this->getRequestData($request);

        $property = Property::create($requestData);

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
        $this->getSelectForForm();

        return view('admin.properties.edit', [
            'property' => $property,
            'delimiter' => '-',
            'categories' => $this->categories,
            'propKinds' => $this->propKinds,
            'propGroups' => $this->propGroups,
            'propTypes' => $this->propTypes,
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

        $requestData = $this->getRequestData($request);

        $property->update($requestData);

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
     * Get options for select in form
     */
    public function getSelectForForm()
    {
        $this->categories = Category::with('children')->where('parent_id', '0')->get();
        $this->propKinds = PropKind::orderby('title', 'asc')->select(['id', 'title'])->get();
        $this->propGroups = PropGroup::orderby('title', 'asc')->select(['id', 'title'])->get();
        $this->propTypes = PropType::orderby('title', 'asc')->select(['id', 'title'])->get();
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

        if ($requestData['category_id'] == 0)
            $requestData['category_id'] = NULL;

        if ($requestData['group_id'] == 0)
            $requestData['group_id'] = NULL;

        return $requestData;
    }
}
