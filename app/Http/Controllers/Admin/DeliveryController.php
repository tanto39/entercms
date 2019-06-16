<?php

namespace App\Http\Controllers\Admin;

use App\Delivery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    use \App\FilterController;
    use \App\SearchController;

    public $indexRoute = 'admin.delivery.index';
    public $prefix = 'Delivery';

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

        $deliveries = new Delivery();

        // Filter
        $deliveries = $this->filterExec($request, $deliveries);

        // Search
        $deliveries = $this->searchByTitle($request, $deliveries);

        $deliveries = $deliveries->paginate(20);

        return view('admin.deliveries.index', [
            'deliveries' => $deliveries,
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

        return view('admin.deliveries.create', [
            'delivery' => [],
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
        $delivery = Delivery::create($request->all());

        $request->session()->flash('success', 'Служба доставки добавлена');
        return redirect()->route('admin.delivery.edit', $delivery);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function show(Delivery $delivery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function edit(Delivery $delivery)
    {
        if (Auth::user()->is_admin == 0)
            return redirect()->route('admin.index');

        return view('admin.deliveries.edit', [
            'delivery' => $delivery,
            'delimiter' => '-'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Delivery $delivery)
    {
        if ($request->delete)
            return $this->destroy($request, $delivery);

        $delivery->update($request->all());

        $request->session()->flash('success', 'Служба доставки изменена');

        if ($request->saveFromList)
            return redirect()->route('admin.delivery.index');

        return redirect()->route('admin.delivery.edit', $delivery);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Delivery $delivery
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Delivery $delivery)
    {
        Delivery::destroy($delivery->id);

        $request->session()->flash('success', 'Служба доставки удалена');
        return redirect()->route('admin.delivery.index');
    }
}
