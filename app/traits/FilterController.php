<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Cookie\CookieJar;

trait FilterController
{
    // Filter properties
    public $arFilter = [];
    public $sortVal = [];

    /**
     * Set filter and sort cookies
     *
     * @param CookieJar $cookieJar
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function filter(CookieJar $cookieJar, Request $request)
    {
        $prefix = $this->prefix;

        $this->getFilterFromCookies($request);

        if($request->reset) {
            $cookieJar->queue($cookieJar->forget('filter'.$prefix));
            $cookieJar->queue($cookieJar->forget('sort'.$prefix));
        }
        else {
            if (count($request->filter) > 0) {
                foreach ($request->filter as $tableName => $filterValue) {
                    // Filter
                    if ($filterValue != "all")
                        $this->arFilter[$tableName] = $filterValue;
                    else
                        unset($this->arFilter[$tableName]);
                }

                $cookieJar->queue(cookie('filter' . $prefix, serialize($this->arFilter), 60));
            }

        // Sort
        if($request->sort && $request->sort != "default")
            $cookieJar->queue(cookie('sort' . $prefix, $request-> sort, 60));
        elseif($request->sort == "default")
            $cookieJar->queue($cookieJar->forget('sort' . $prefix));

        }

        return redirect()->route($this->indexRoute);
    }

    /**
     * Execute queries to DB
     *
     * @param Request $request
     * @param $selectTable
     * @return mixed
     */
    public function filterExec(Request $request, $selectTable)
    {
        // Get filter and sort from cookies
        $this->getFilterFromCookies($request);

        // Filter
        if (is_array($this->arFilter)) {
            foreach ($this->arFilter as $tableName => $filterValue) {
                if ($filterValue == "NULL")
                    $filterValue = null;

                $selectTable = $selectTable->where($tableName, $filterValue);
            }
        }

        // Sort
        switch ($this->sortVal) {
            case "dateUp":
                $selectTable = $selectTable->orderby('updated_at', 'desc');
                break;

            case "dateDown":
                $selectTable = $selectTable->orderby('updated_at', 'asc');
                break;

            case "title":
                $selectTable = $selectTable->orderby('title', 'asc');
                break;

            default:
                $selectTable = $selectTable->orderby('order', 'asc')->orderby('updated_at', 'desc');
        }

        return $selectTable;
    }

    /**
     * Get filter and sort values from cookies and unserialize filter
     *
     * @param Request $request
     */
    public function getFilterFromCookies(Request $request) {
        $this->arFilter = unserialize($request->cookie('filter' . $this->prefix));
        $this->sortVal = $request->cookie('sort' . $this->prefix);
    }

}
