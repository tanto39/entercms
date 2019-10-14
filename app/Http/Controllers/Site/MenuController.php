<?php

namespace App\Http\Controllers\Site;

use App;
use App\Item;
use App\Category;
use App\Review;
use App\MenuType;
use App\Menu;
use App\MenuItem;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    protected static $_instance;
    public $menu = [];

    private function __construct() {}

    /**
     * @return MenuController
     */
    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    /**
     * Create menu tree
     */
    public function createMenuTree()
    {
        $requestUri = url()->getRequest()->path();

        if ($requestUri == '/')
            $uri = $requestUri;
        else
            $uri = '/' . $requestUri;

        $menuItems = new MenuItem();
        $resultMenuItems = [];

        $menuItems = $menuItems->with('menu')
        ->orderby('order', 'asc')->orderby('updated_at', 'desc')
        ->get()
        ->toArray();

        // Grouping by menu
        foreach ($menuItems as $key=>$menuItem) {
            $resultMenuItems[$menuItem['menu']['slug']][$menuItem['id']] = $menuItem;

            // Set active item
            if ($menuItem['href'] == $uri)
                $resultMenuItems[$menuItem['menu']['slug']][$menuItem['id']]['active'] = 'Y';
            else
                $resultMenuItems[$menuItem['menu']['slug']][$menuItem['id']]['active'] = 'N';
        }

        // Croup by child
        foreach ($resultMenuItems as $menuTitle=>$menuBlock) {
            foreach ($menuBlock as $menuItemId => $menuItem) {
                if (array_key_exists($menuItem['parent_id'], $menuBlock)) {
                    $resultMenuItems[$menuTitle][$menuItem['parent_id']]['children'][$menuItemId] = $menuItem;
                    unset($resultMenuItems[$menuTitle][$menuItemId]);
                }
            }
        }

        $this->menu = $resultMenuItems;
    }

    /**
     * @return array
     */
    public function getMenuTree()
    {
        return $this->menu;
    }
}
