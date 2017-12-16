<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use App\StatusOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    use \App\FilterController;
    use \App\SearchController;

    public $indexRoute = 'admin.order.index';
    public $prefix = 'Order';

    /**
     * Display a listing of the resource
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $orders = new Order();

        // Filter
        $orders = $this->filterExec($request, $orders);

        // Search
        $orders = $this->searchByTitle($request, $orders);

        $orders = $orders->paginate(20);

        return view('admin.orders.index', [
            'orders' => $orders,
            'status_orders' => StatusOrder::orderby('id', 'asc')->select(['id', 'title'])->get(),
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
        return view('admin.orders.create', [
            'status_orders' => StatusOrder::orderby('id', 'asc')->select(['id', 'title'])->get(),
            'order' => [],
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
        $order = Order::create($request->all());

        $request->session()->flash('success', 'Заказ добавлен');
        return redirect()->route('admin.order.edit', $order);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        return view('admin.orders.edit', [
            'status_orders' => StatusOrder::orderby('id', 'asc')->select(['id', 'title'])->get(),
            'order' => $order,
            'delimiter' => '-'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        if ($request->delete)
            return $this->destroy($request, $order);

        $order->update($request->all());

        $request->session()->flash('success', 'Заказ отредактирован');

        if ($request->saveFromList)
            return redirect()->route('admin.order.index');

        return redirect()->route('admin.order.edit', $order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Order $order)
    {
        Order::destroy($order->id);
        $request->session()->flash('success', 'Заказ удален');
        return redirect()->route('admin.order.index');
    }
}
