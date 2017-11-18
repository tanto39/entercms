<?php

namespace App;

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

    /**
     * Set properties array and serialize them
     *
     * @param array $properties
     * @return string
     */
    public function setProperties($properties = [])
    {
        $strProp = "";

        if(count($properties) > 0) {
            foreach ($properties as $propId=>$property) {
                $propOb = Property::where('id', $propId)->get()->toArray()[0];

                if ($propOb) {
                    $this->arProps[$propId] = $propOb;
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
    public function getProperties($selectTable, $fieldLinkId, $fieldParentId, $propKind, $strProp = "", $categoryId = NULL)
    {
        $propValues = [];

        $propValues = unserialize($strProp);

        $this->getPropList($categoryId, $fieldLinkId, $selectTable, $fieldParentId, $propKind);

        if (count($this->propList) > 0) {
            foreach ($this->propList as $key=>$property) {
                $this->arProps[$property["id"]] = $property;

                if (!empty($propValues[$property["id"]]))
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
            if ($property['is_insert'] == 1)
                $this->propList[] = $property;
        }

        if ($categoryId) {
            // Get properties for all categories
            $this->getPropList(NULL, $fieldLinkId, $selectTable, $fieldParentId, $propKind);
            $parentId = $selectTable->where('id', $categoryId)->select([$fieldParentId])->get()->toArray()[0][$fieldParentId];
        }

        if (isset($parentId))
            $this->getPropList($parentId, $fieldLinkId, $selectTable, $fieldParentId, $propKind);
    }
}
