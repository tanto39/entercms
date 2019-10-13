<?php

namespace App\Http\Controllers\Site;

use App;
use App\Region;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;

class RegionController extends Controller
{
    protected static $_instance;
    public $selectedRegion = [];
    public $regions = [];

    private function __construct() {}

    /**
     * @return RegionController
     */
    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    /**
     * Get region list
     *
     * @return array
     */
    public function getRegionListFromDB()
    {
        $regions= new Region();

        $regions = $regions->orderby('order', 'asc')->orderby('updated_at', 'desc')
        ->get()
        ->toArray();

        $this->regions = $regions;
    }


    public function getRegionFromCookie()
    {
        $region_id = Cookie::get('selectedRegion');

        if (!empty($region_id))
            $this->selectedRegion = Region::where('id', $region_id)->get()->toArray();
    }

    public function getSelectedRegion()
    {
        return $this->selectedRegion;
    }

    public function getRegionList()
    {
        return $this->regions;
    }
}
