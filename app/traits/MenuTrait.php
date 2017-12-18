<?php

namespace App;

use App\MenuItem;
use Illuminate\Support\Facades\Auth;

trait MenuTrait
{
    /**
     * Destroy item menu with menu delete
     *
     * @param $fieldName
     * @param $fieldValue
     */
    public function deleteMenuItem($fieldName, $fieldValue)
    {
        $menuItems = [];
        $arMenuItemId = [];
        $menuItems = MenuItem::where($fieldName, $fieldValue)->select(['id'])->get()->toArray();

        if (!empty($menuItems)) {
            foreach ($menuItems as $menuItem) {
                $arMenuItemId[] = $menuItem['id'];
            }
            MenuItem::destroy($arMenuItemId);
        }
    }
}
