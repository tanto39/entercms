<?php

namespace App;

use App\Property;

/**
 * Set and get property array
 *
 * Trait HandlePropertyController
 * @package App
 */
trait HandlePropertyController
{
    use \App\PropEnumController;

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
        $propOb = [];
        $obProps = [];
        $oldImages = [];

        if (count($properties) > 0) {

            foreach ($properties as $propId => $property) {

                if (!is_null($selectTable)) {
                    $obProps = unserialize($selectTable
                        ->where('id', $elementId)
                        ->select('properties')
                        ->get()
                        ->toArray()[0]['properties']);
                }
                $propOb = Property::where('id', $propId)->get()->toArray()[0];

                $this->arProps = $obProps;

                $this->arProps[$propId] = $propOb;

                // Image properties
                if ($propOb['type'] == PROP_TYPE_IMG) {

                    if (isset($obProps[$propId]['value']))
                        $oldImages = $obProps[$propId]['value'];

                    $this->arProps[$propId]['value'] = $this->LoadImg($property, $oldImages);
                }
                elseif($propOb['type'] == PROP_TYPE_FILE) {
                    $this->arProps[$propId]['value'] = $this->LoadFile($property);
                }
                else {
                    $this->arProps[$propId]['value'] = $property;
                }

            }
            $strProp = serialize($this->arProps);
        }

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

        $propValues = unserialize($strProp);

        $this->getPropList($categoryId, $fieldLinkId, $selectTable, $fieldParentId, $propKind);

        if (count($this->propList) > 0) {
            foreach ($this->propList as $key=>$property) {
                $this->arProps[$property["id"]] = $property;

                // Enumeration property
                if ($property['type'] == PROP_TYPE_LIST) {
                    $this->getListValues($property["id"]);
                    $this->arProps[$property["id"]]['arList'] = $this->propEnums;
                }

                if (!empty($propValues[$property["id"]]) && isset($propValues[$property["id"]]['value']))
                    $this->arProps[$property["id"]]['value'] = $propValues[$property["id"]]['value'];
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
            if (($this->insertCount === 0 && $property['is_insert'] === 0) || ($property['is_insert'] == 1))
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
}
