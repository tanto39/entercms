<?php

namespace App\Http\Controllers\Site;

use App;
use Illuminate\Http\Request;
use Illuminate\Cookie\CookieJar;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;

class SetRegionController extends Controller
{
    public static function setRegion(CookieJar $cookieJar, Request $request)
    {
        $cookieJar->queue(cookie('selectedRegion', $request->regionId, 1000000));
        return redirect()->back();
    }
}
