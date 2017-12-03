<?php

namespace App;

use App\Category;
use App\Property;
use App\PropGroup;
use phpDocumentor\Reflection\Types\Object_;

/**
 * Set and get property array
 *
 * Trait HandlePropertyController
 * @package App
 */
trait HandlePropertyController
{
    public $arProps = [];
    public $propList = [];
    public $insertCount = 0;

    /**
     * Set properties array and serialize them
     *
     * @param array $properties
     * @param $selectTable
     * @param $elementId
     * @return string
     */
    public function setProperties($properties = [], $selectTable = NULL, $elementId = NULL)
    {
        $strProp = "";
        $obProps = [];
        $oldImages = [];
        $arProps = [];

        if (!is_null($selectTable)) {
            $obProps = unserialize($selectTable
                ->where('id', $elementId)
                ->select('properties')
                ->get()
                ->toArray()[0]['properties']);
        }
        $this->arProps = $obProps;

        if (count($properties) > 0) {
            foreach ($properties as $propId => $property) {
                $propOb = [];
                $propGroupName = PROP_GROUP_NAME_ALL;

                $propOb = Property::where('id', $propId)->get()->toArray()[0];

                if (!is_null($propOb['group_id']))
                    $propGroupName = PropGroup::where('id', $propOb['group_id'])->select(['id', 'title'])->get()->toArray()[0]['title'];

                $this->arProps[$propGroupName][$propId] = $propOb;

                // Image properties
                if ($propOb['type'] == PROP_TYPE_IMG) {
                    if (isset($obProps[$propGroupName][$propId]['value']))
                        $oldImages = $obProps[$propGroupName][$propId]['value'];

                    $this->arProps[$propGroupName][$propId]['value'] = $this->LoadImg($property, $oldImages);
                }
                if($propOb['type'] == PROP_TYPE_FILE) {
                    $this->arProps[$propGroupName][$propId]['value'] = $this->LoadFile($property);
                }
                if(($propOb['type'] !== PROP_TYPE_FILE) && ($propOb['type'] !== PROP_TYPE_IMG)){
                    $this->arProps[$propGroupName][$propId]['value'] = $property;
                }
            }
        }
        $strProp = serialize($this->arProps);
        return $strProp;
    }

    /**
     * Get properties
     *
     * @param $selectTable
     * @param $fieldLinkId
     * @param $fieldParentId
     * @param $propKind
     * @param string $strProp
     * @param null $categoryId
     */
    public function getProperties($selectTable, $propKind, $fieldLinkId = 'category_id', $fieldParentId = 'parent_id', $strProp = "", $categoryId = NULL)
    {
        $propValues = [];
        $propGroupName = PROP_GROUP_NAME_ALL;

        $propValues = unserialize($strProp);

        $this->getPropList($categoryId, $fieldLinkId, $selectTable, $fieldParentId, $propKind);

        if (count($this->propList) > 0) {
            foreach ($this->propList as $key=>$property) {
                if (!is_null($property['group_id']))
                    $propGroupName = PropGroup::where('id', $property['group_id'])->get()->toArray()[0]['title'];

                $this->arProps[$propGroupName][$property["id"]] = $property;

                // Enumeration property
                if ($property['type'] == PROP_TYPE_LIST) {
                    $this->getListValues($property["id"]);
                    $this->arProps[$propGroupName][$property["id"]]['arList'] = $this->propEnums;
                }

                if (!empty($propValues[$propGroupName][$property["id"]]) && isset($propValues[$propGroupName][$property["id"]]['value']))
                    $this->arProps[$propGroupName][$property["id"]]['value'] = $propValues[$propGroupName][$property["id"]]['value'];
            }


        }
    }

    /**
     * Get tree of properties for categories
     *
     * @param $categoryId
     * @param $fieldLinkId
     * @param $selectTable
     * @param $fieldParentId
     * @param $propKind
     */
    public function getPropList($categoryId, $fieldLinkId, $selectTable, $fieldParentId, $propKind)
    {
        $categoryProps = [];

        $categoryProps = Property::orderby('id', 'asc')
            ->where($fieldLinkId, $categoryId)
            ->where('prop_kind', $propKind)
            ->get()
            ->toArray();

        foreach ($categoryProps as $key=>$property) {
            if ( (($this->insertCount === 0 && $property['is_insert'] === 0) || ($property['is_insert'] == 1)) && (!in_array($property, $this->propList)) )
                $this->propList[] = $property;
        }

        if ($categoryId) {
            // Get properties for all categories
            $this->getPropList(NULL, $fieldLinkId, $selectTable, $fieldParentId, $propKind);
            $parentId = $selectTable->where('id', $categoryId)->select([$fieldParentId])->get()->toArray()[0][$fieldParentId];
        }

        if (isset($parentId))
            $this->getPropList($parentId, $fieldLinkId, $selectTable, $fieldParentId, $propKind);

        $this->insertCount++;
    }

    /**
     * Delete propertyes of category with destroy category
     *
     * @param $selectTable
     */
    public function deletePropertyWithDestroy($selectTable)
    {
        $propOb = Property::where('category_id', $selectTable->id)->select(['id', 'type'])->get()->toArray();
        $propIds = [];

        foreach ($propOb as $key=>$prop) {
            if ($prop['type'] == PROP_TYPE_LIST)
                $this->deleteListValues($prop['id']);

            $propIds[] = $prop['id'];
        }

        Property::destroy($propIds);
    }

    /**
     * Delete property values from element with change property kind
     *
     * @param $requestData
     */
    public function deletePropertyWithChangeType($requestData, $categoryId)
    {
        $arProperties = [];
        $arChildId = [];

        $selectTable = new Object_();
        $filterField = "id";

        switch ($requestData['prop_kind']) {
            case PROP_KIND_CATEGORY:
                $selectTable = new Category();
                if (!is_null($categoryId))
                    $arChildId = $selectTable->where('parent_id', $categoryId)->select(['id'])->get()->toArray();
                else
                    $arChildId = $selectTable->select(['id'])->get()->toArray();
                break;
        }

        // Get property array from DB
        if (!is_null($categoryId))
            $arElements = $selectTable->where($filterField, $categoryId)->select(['id', 'properties'])->get()->toArray();
        else
            $arElements = $selectTable->select(['id', 'properties'])->get()->toArray();

        foreach ($arElements as $key=>$arElement) {
            $arProperties = unserialize($arElement['properties']);

            if (empty($arProperties))
                continue;

            foreach ($arProperties as $propGroup=>$arProperty) {
                if (key_exists($requestData['id'], $arProperty)) {

                    // Delete images
                    if ($requestData['old_type'] == PROP_TYPE_IMG) {
                        foreach ($arProperty[$requestData['id']]['value'] as $keyImg=>$arImg) {
                            $this->deleteMultipleImg($arProperty[$requestData['id']]['value'], $arImg['MIDDLE']);
                        }
                    }

                    // Delete files
                    if ($requestData['old_type'] == PROP_TYPE_FILE)
                        $this->deleteFileFromServer($arProperty[$requestData['id']]['value']);

                    // Delete enums values
                    if ($requestData['old_type'] == PROP_TYPE_LIST)
                        $this->deleteListValues($requestData['id']);

                    // Delete property values from value array
                    unset($arProperties[$propGroup][$requestData['id']]);

                    $selectTable->where('id', $arElement['id'])->update(['properties' => serialize($arProperties)]);
                }
            }
        }

        // Recurce for child categories
        if (!empty($arChildId)) {
            foreach ($arChildId as $childId)
                $this->deletePropertyWithChangeType($requestData, $childId['id']);
        }
    }

}
