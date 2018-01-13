<?php

namespace App\Http\Controllers\Admin;

use App\Property;
use App\PropGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PropGroupController extends Controller
{
    use \App\ImgController;
    use \App\FileController;
    use \App\FilterController;
    use \App\SearchController;
    use \App\PropEnumController;
    use \App\HandlePropertyController;

    public $indexRoute = 'admin.propgroup.index';
    public $prefix = 'PropGroup';

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

        $propgroups = new PropGroup();

        // Filter
        $propgroups = $this->filterExec($request, $propgroups);

        // Search
        $propgroups = $this->searchByTitle($request, $propgroups);

        $propgroups = $propgroups->paginate(20);

        return view('admin.propgroups.index', [
            'propgroups' => $propgroups,
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
        if (Auth::user()->is_admin == 0)
            return redirect()->route('admin.index');

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
        if ($request->delete)
            return $this->destroy($request, $propgroup);

        $propgroup->update($request->all());

        $request->session()->flash('success', 'Группа отредактирована');

        if ($request->saveFromList)
            return redirect()->route('admin.propgroup.index');

        return redirect()->route('admin.propgroup.edit', $propgroup);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param PropGroup $propgroup
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, PropGroup $propgroup)
    {
        // Delete props
        $arProps = Property::where('group_id', $propgroup->id)->get()->toArray();

        if (!empty($arProps)) {
            foreach ($arProps as $key=>$arProp) {
                if ($arProp['type'] == PROP_TYPE_LIST)
                    $this->deleteListValues($arProp['id']);

                $arProp['old_type'] = $arProp['type'];
                $this->deletePropertyWithChange($arProp, null);

                Property::destroy($arProp['id']);
            }
        }

        // Delete group
        PropGroup::destroy($propgroup->id);
        $request->session()->flash('success', 'Группа удалена');
        return redirect()->route('admin.propgroup.index');
    }
}
