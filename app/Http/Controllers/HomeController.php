<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Category;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mainPage = Item::with('reviews')->where('slug', '/')->get();

        if(isset($mainPage[0])) {
            return view('public/items/item', [
                'result' => $mainPage[0]
            ]);
        }
    }
}
