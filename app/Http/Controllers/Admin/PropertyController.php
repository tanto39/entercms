<?php

namespace App\Http\Controllers\Admin;

use App\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\PropKind;
use App\PropGroup;
use App\PropType;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    use \App\ImgController;
    use \App\FileController;
    use \App\FilterController;
    use \App\SearchController;
    use \App\PropEnumController;
    use \App\HandlePropertyController;

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
        if (Auth::user()->is_admin == 0)
            return redirect()->route('admin.index');

        $this->getSelectForForm();

        $properties = new Property();

        // Filter
        $properties = $this->filterExec($request, $properties);

        // Search
        $properties = $this->searchByTitle($request, $properties);

        $properties = $properties->paginate(20);

        return view('admin.properties.index', [
            'properties' => $properties,
            'searchText' => $this->searchText,
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
        if (Auth::user()->is_admin == 0)
            return redirect()->route('admin.index');

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
        if (Auth::user()->is_admin == 0)
            return redirect()->route('admin.index');

        $this->getSelectForForm();

        // Get properties of type list values
        if ($property->type == PROP_TYPE_LIST)
            $this->getListValues($property->id);

        return view('admin.properties.edit', [
            'property' => $property,
            'delimiter' => '-',
            'categories' => $this->categories,
            'propKinds' => $this->propKinds,
            'propGroups' => $this->propGroups,
            'propTypes' => $this->propTypes,
            'propEnums' => $this->propEnums
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

        // Change property name with change title
        if ($requestData['old_title'] != $requestData['title'])
            $this->deletePropertyWithChange($requestData, $requestData['category_id'], true);

        // Change property order with change order in element
        if ($requestData['old_order'] != $requestData['order'])
            $this->deletePropertyWithChange($requestData, $requestData['category_id'], false, true);

        // Delete properties with change type
        if ($requestData['old_type'] != $requestData['type']) {
            $this->deletePropertyWithChange($requestData, $requestData['category_id']);

            if ( $requestData['old_type'] == PROP_TYPE_LIST)
                $this->deleteListValues($requestData['id']);
        }

        // Delete properties with change category id
        if ($requestData['old_category_id'] != $requestData['category_id'])
            $this->deletePropertyWithChange($requestData, null, true);

        if (isset($requestData['prop_enums']) && $requestData['old_type'] == $requestData['type'])
            $this->updateListValues($requestData['prop_enums']);

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
        if ($property->type == PROP_TYPE_LIST)
            $this->deleteListValues($property->id);

        $requestData = $request->all();
        $this->deletePropertyWithChange($requestData, null);

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

        if (isset($requestData['prop_enums_add']))
            $this->addListValues($requestData['prop_enums_add'], $requestData['id']);

        return $requestData;
    }

}
