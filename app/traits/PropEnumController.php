<?php

namespace App;

trait PropEnumController
{
    public $propEnums = [];

    /**
     * Add list values in property enumeration table
     *
     * @param $arList
     */
    public function addListValues($arList, $propId)
    {
        foreach ($arList as $key=>$list) {
            if (!empty($list))
                PropEnum::create([
                    'title' => $list,
                    'slug' => '',
                    'prop_id' => $propId,
                    'order' => 10000
                ]);
        }
    }

    /**
     * Get list values from property enumeration table
     *
     * @param $propId
     */
    public function getListValues($propId)
    {
        $this->propEnums = PropEnum::orderby('title', 'asc')
            ->where('prop_id', $propId)
            ->select(['id', 'title'])
            ->get()
            ->toArray();
    }

    /**
     * Update list values from property enumeration table
     *
     * @param $arPropList
     */
    public function updateListValues($arPropList)
    {
        if (count($arPropList) > 0) {
            foreach ($arPropList as $listId=>$listTitle) {
                if (empty($listTitle))
                    PropEnum::destroy($listId);
                else
                    PropEnum::where('id', $listId)->update(['title'=>$listTitle]);
            }
        }
    }

    /**
     * Delete values from enumeration table
     *
     * @param $propId
     */
    public function deleteListValues($propId)
    {
        $arListId = [];
        $arList = PropEnum::where('prop_id', $propId)->get();

        if (!empty($arList)) {
            foreach ($arList as $key=>$list) {
                $arListId[] = $list->id;
            }
            PropEnum::destroy($arListId);
        }
    }

    /**
     * Get list title for public DEPRECATED NOT USED
     *
     * @param $propId
     * @param $arEnumId
     * @return mixed
     */
    public function getListValue($propId, $arEnumId)
    {
        $propValue = [];

        $propValues = new PropEnum();

        foreach ($arEnumId as $key=>$enumId) {
            $propValues = $propValues->orWhere('id', $enumId);
        }

        $propValues = $propValues->select(['id', 'title', 'slug'])->get()->toArray();

        return $propValues;
    }
}
