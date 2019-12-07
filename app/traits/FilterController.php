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

    /**
     * Smart filter execution
     *
     * @param Request $request
     * @param $selectTable
     * @return mixed
     */
    public function smartFilterExec(Request $request, $selectTable)
    {
        $arProperty = $request->get('property');

        $regex = '';
        $ifToMoreThenFrom = "";

        if (isset($arProperty)) {
            foreach ($arProperty as $propId=>$selectValue) {
                // Number properties
                if (isset($selectValue['from']) || isset($selectValue['to'])) {
                    if (is_null($selectValue['from']))
                        $selectValue['from'] = 0;
                    if (is_null($selectValue['to']))
                        $selectValue['to'] = 100000;

                    // Set regex for number
                    $arFrom = str_split((string)$selectValue['from']);
                    $arTo = str_split((string)$selectValue['to']);

                    $countNumberFrom = count($arFrom);
                    $countNumberTo = count($arTo);

                    if ($countNumberTo > $countNumberFrom)
                        $ifToMoreThenFrom = '-9';

                    $regexNumDiap = '"';

                    // First number diapason
                    foreach ($arFrom as $key=>$number)
                        $regexNumDiap .= '['.$number.$ifToMoreThenFrom.']';

                    $regexNumDiap .= '"';

                    for ($i = 1; $i < $countNumberFrom; $i++) {

                        for ($j = 0; $j < $countNumberFrom; $j++) {
                            if ($j == 0) {
                                $regexNumDiap .= '|"['.$arFrom[0].$ifToMoreThenFrom.']';
                            }
                            else {
                                if ($j == $i) {
                                    $regexNumDiap .= '['.($arFrom[$j]+1).'-9]';
                                }
                                elseif ($j < $i) {
                                    $regexNumDiap .= '['.$arFrom[$j].'-9]';
                                }
                                else {
                                    $regexNumDiap .= '[0-9]';
                                }
                            }
                        }

                        $regexNumDiap .= '"';
                    }

                    // Middle number diapasons
                    if ($countNumberTo > ($countNumberFrom + 1)) {

                        $countMiddleDiap = $countNumberTo - $countNumberFrom - 1;

                        for ($i = 0; $i < $countMiddleDiap; $i++) {
                            $regexNumDiap .= '|"[1-9]';

                            for ($j = 0; $j < ($countNumberFrom + $i); $j++) {
                                $regexNumDiap .= '[0-9]';
                            }

                            $regexNumDiap .= '"';
                        }

                        if ($arTo[0] != 1)
                            $regexNumDiap .= '|"[0-' . ($arTo[0] - 1) . ']';
                        else
                            $regexNumDiap .= '|"';

                        $arTo1 = $arTo;
                        array_pop($arTo1);

                        foreach ($arTo1 as $key=>$number) {
                            $regexNumDiap .= '[0-9]';
                        }

                        $regexNumDiap .= '"';
                    }

                    // End number diapasons
                    if ($arTo[0] != 1) {
                        for ($i = 1; $i < $arTo[0]; $i++) {
                            if ($i > $arFrom[0] || $countNumberTo != $countNumberFrom) {
                                $regexNumDiap .= '|"['.$i.']';
                                for ($j = 1; $j < $countNumberTo; $j++) {
                                    $regexNumDiap .= '[0-9]';
                                }
                                $regexNumDiap .= '"';
                            }
                        }
                    }

                    for ($i = 1; $i < $countNumberTo; $i++) {

                        for ($j = 0; $j < $countNumberTo; $j++) {
                            if ($j == 0) {
                                if ($countNumberTo != $countNumberFrom)
                                    $regexNumDiap .= '|"[0-' . $arTo[0] . ']';
                                else
                                    $regexNumDiap .= '|"[' . $arTo[0] . ']';
                            } else {
                                if ($j == $i) {
                                    if ($arTo[$j] != 0) {
                                        if ($j == ($countNumberTo-1))
                                            $regexNumDiap .= '[0-' . $arTo[$j] . ']';
                                        else
                                            $regexNumDiap .= '[0-' . ($arTo[$j] - 1) . ']';
                                    }
                                    else
                                        $regexNumDiap .= '[0]';
                                } elseif ($j < $i) {
                                    $regexNumDiap .= '[0-' . $arTo[$j] . ']';
                                } else {
                                    if (($arTo[$j] == 0 && ($arTo[$i] == 0)) || ($arTo[$j - 1] == 0 && ($arTo[$i] == 0)))
                                        $regexNumDiap .= '[0]';
                                    else
                                        $regexNumDiap .= '[0-9]';
                                }
                            }
                        }
                        $regexNumDiap .= '"';
                    }

                    /**
                     * https://regex101.com/r/XXOqPc/8
                     *
                     * if set 203-25030 regex is "i:14;a:[^}]*("[2-9][0-9][3-9]"|"[2-9][1-9][0-9]"|"[2-9][0-9][4-9]"|"[1-9][0-9][0-9][0-9]"|"[0-1][0-9][0-9][0-9][0-9]"|"[1][0-9][0-9][0-9][0-9]"|"[0-2][0-4][0-9][0-9][0-9]"|"[0-2][0-5][0][0][0]"|"[0-2][0-5][0-0][0-2][0-9]"|"[0-2][0-5][0-0][0-3][0]");} ◀"
                     */
                    $regex = 'i:'.$propId.';a:[^}]*('.$regexNumDiap.');}';
                    $selectTable = $selectTable->where('properties', 'REGEXP', $regex);
                }
                // List properties
                elseif (isset($selectValue['arListValues'])) {
                    foreach ($selectValue['arListValues'] as $key=>$listValue) {
                        $count = strlen($listValue);
                        $regexList = 'i:'.$key.';s:'.$count.':"'.$listValue.'"';

                        $regex = 'i:'.$propId.';a:[^}]*('.$regexList.')[^}]*}';
                        $selectTable = $selectTable->where('properties', 'REGEXP', $regex);
                    }
                }
                // Other properties
                elseif (isset($selectValue['text'])) {
                    $regex = 'i:'.$propId.';a:[^}]*("'.$selectValue['text'].'");}';
                    $selectTable = $selectTable->where('properties', 'REGEXP', $regex);
                }
            }
        }

        //$requestUri = url()->getRequest()->getRequestUri();

        $selectTable = $selectTable->get(); //paginate(PAGE_COUNT);
        //$selectTable->setPath($requestUri);

        return $selectTable;
    }

    /**
     * Select properties for smart filter
     *
     * @param $request
     * @param $categoryId
     * @return mixed
     */
    public function getFilterProperties($request, $categoryId)
    {
        $arPropertyGet = $request->get('property');

        $properties = Property::where('smart_filter', 1)
            ->whereIn('category_id', [$categoryId, CATALOG_ID])
            ->where('prop_kind', PROP_KIND_ITEM)
            ->orderby('order', 'asc')
            ->select(['id', 'order', 'title', 'slug', 'type'])->get()->toArray();

        foreach ($properties as $key=>$property) {
            // List properties
            if ($property['type'] == PROP_TYPE_LIST) {
                $this->getListValues($property["id"]);

                $properties[$key]['arValues'] = $this->propEnums;

                // Set selected values
                foreach ($properties[$key]['arValues'] as $keyProp=>$arListValue) {
                    if (isset($arPropertyGet[$property["id"]]) && in_array($arListValue['id'], $arPropertyGet[$property["id"]]['arListValues']))
                        $properties[$key]['arValues'][$keyProp]['selected'] = 'Y';
                    else
                        $properties[$key]['arValues'][$keyProp]['selected'] = 'N';
                }
            }
            // Number properties
            elseif ($property['type'] == PROP_TYPE_NUM) {
                $properties[$key]['values']['from'] = $arPropertyGet[$property["id"]]['from'];
                $properties[$key]['values']['to'] = $arPropertyGet[$property["id"]]['to'];
            }
            else {
                $properties[$key]['text'] = $arPropertyGet[$property["id"]]['text'];
            }
        }

        return $properties;
    }

}
