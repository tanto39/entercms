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
        if($request->regionId != 0)
            $cookieJar->queue(cookie('selectedRegion', $request->regionId, 1000000));
        else
            Cookie::queue(Cookie::forget('selectedRegion'));

        return redirect()->back();
    }
}
