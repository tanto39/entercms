<?php

namespace App\Http\Controllers\Site;

use App;
use App\Http\Controllers\Controller;

class TemplateController extends Controller
{
    protected static $_instance;
    public $selectedRegion = [];
    public $regions = [];
    public $uri = '';
    public $isInstance = 'N'; // Y
    public $contacts = [
        'phone' => PHONE,
        'mail' => MAIL,
        'address' => ADDRESS,
        'map' => COMPANY_MAP,
        'companyWhere' => COMPANY_WHERE
    ];

    private function __construct() {}

    /**
     * @return TemplateController
     */
    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    /**
     * Set template variables
     */
    public function setTemplateVariables() {
        // uri
        $this->uri = url()->getRequest()->path();

        // Init menu instance
        MenuController::getInstance()->createMenuTree();

        // Init region instance
        if(USE_REGION == "Y") {
            RegionController::getInstance()->getRegionListFromDB();
            RegionController::getInstance()->getRegionFromCookie();
            $this->regions = RegionController::getInstance()->getRegionList();
            $this->selectedRegion = RegionController::getInstance()->getSelectedRegion();

            if(!empty($this->selectedRegion))
                $this->contacts = [
                    'phone' => $this->selectedRegion[0]['phone'],
                    'mail' => $this->selectedRegion[0]['mail'],
                    'address' => $this->selectedRegion[0]['address'],
                    'map' => $this->selectedRegion[0]['regmap'],
                    'companyWhere' => $this->selectedRegion[0]['regwhere']
                ];
        }

        $this->isInstance = 'Y';
    }


}
