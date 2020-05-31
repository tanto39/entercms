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
        $propGroupName = PROP_GROUP_NAME_ALL;

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

            // Sort properties by order
            foreach ($this->arProps as $groupName => $aProp)
                uasort($this->arProps[$groupName], ['self', 'sortArray']);
        }

        $strProp = serialize($this->arProps);
        return $strProp;
    }

    /**
     * Get properties
     *
     * @param $selectTable
     * @param $propKind
     * @param string $strProp
     * @param null $categoryId
     */
    public function getProperties($selectTable, $propKind, $strProp = "", $categoryId = NULL)
    {
        $propValues = [];

        $propValues = unserialize($strProp);

        $this->getPropList($categoryId, $selectTable, $propKind);

        if (count($this->propList) > 0) {
            foreach ($this->propList as $key=>$property) {
                if (!is_null($property['group_id']))
                    $propGroupName = PropGroup::where('id', $property['group_id'])->get()->toArray()[0]['title'];
                else
                    $propGroupName = PROP_GROUP_NAME_ALL;

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
     * @param $selectTable
     * @param $propKind
     */
    public function getPropList($categoryId, $selectTable, $propKind)
    {
        $categoryProps = [];

        $categoryProps = Property::orderby('id', 'asc')
            ->where("category_id", $categoryId)
            ->where('prop_kind', $propKind)
            ->get()
            ->toArray();

        foreach ($categoryProps as $key=>$property) {
            if ( (($this->insertCount === 0 && $property['is_insert'] === 0) || ($property['is_insert'] == 1)) && (!in_array($property, $this->propList)) )
                $this->propList[] = $property;
        }

        if ($categoryId) {
            // Get properties for all categories
            $this->getPropList(NULL, $selectTable, $propKind);
            $parentId = Category::where('id', $categoryId)->select(['parent_id'])->get()->toArray()[0]['parent_id'];
        }

        if (isset($parentId))
            $this->getPropList($parentId, $selectTable, $propKind);

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
     * Delete property values from element with change property kind, insert (or change property title in elements)
     *
     * @param $requestData
     * @param $isChangeCategoryId - if change category id
     * @param $isChangeTitle - if change property title
     * @param $isChangeTitle - if change property order
     * @param $categoryId
     */
    public function deletePropertyWithChange($requestData, $categoryId, $isChangeTitle = false, $isChangeOrder = false, $isChangeCategoryId = false)
    {
        $arProperties = [];
        $arChildId = [];

        $filterField = "id";

        switch ($requestData['prop_kind']) {
            case PROP_KIND_CATEGORY:
                $selectTable = new Category();

                // Get property array from DB
                if (!is_null($categoryId))
                    $arElements = $selectTable->where($filterField, $categoryId)->select(['id', 'properties'])->get()->toArray();
                else
                    $arElements = $selectTable->select(['id', 'properties'])->get()->toArray();

                foreach ($arElements as $key=>$arElement) {

                    // Get properties
                    $arProperties = unserialize($arElement['properties']);

                    if (empty($arProperties) || ($isChangeCategoryId && ($categoryId == $arElement['id'])))
                        continue;

                    // Get child categories
                    if (!is_null($categoryId))
                        $arChildId = $selectTable->where('parent_id', $arElement['id'])->select(['id'])->get()->toArray();
                    else
                        $arChildId = $selectTable->select(['id'])->get()->toArray();

                    // Change or delete properties from element
                    if ($isChangeTitle == true || $isChangeOrder == true)
                        $this->changeProperties($arProperties, $requestData, $selectTable, $arElement);
                    else
                        $this->deleteArProperties($arProperties, $requestData, $selectTable, $arElement);

                    // Recurce for child categories
                    if (!empty($arChildId)) {
                        foreach ($arChildId as $childId)
                            $this->deletePropertyWithChange($requestData, $childId['id'], $isChangeTitle, $isChangeOrder, $isChangeCategoryId);
                    }
                }

                break;

            case PROP_KIND_ITEM:
                $selectTable = new Item();
                $categoryTable = new Category();
                $filterField = "category_id";

                // Get property array from DB
                if (!is_null($categoryId))
                    $arElements = $selectTable->where($filterField, $categoryId)->select(['id', 'properties'])->get()->toArray();
                else
                    $arElements = $selectTable->select(['id', 'properties'])->get()->toArray();

                // Get child categories
                if (!is_null($categoryId))
                    $arChildId = $categoryTable->where('parent_id', $categoryId)->select(['id'])->get()->toArray();
                else
                    $arChildId = $selectTable->select(['id'])->get()->toArray();

                foreach ($arElements as $key=>$arElement) {

                    // Get properties
                    $arProperties = unserialize($arElement['properties']);

                    if (empty($arProperties) || ($isChangeCategoryId && ($categoryId == $arElement['id'])))
                        continue;

                    // Change or delete properties from element
                    if ($isChangeTitle == true || $isChangeOrder == true)
                        $this->changeProperties($arProperties, $requestData, $selectTable, $arElement);
                    else
                        $this->deleteArProperties($arProperties, $requestData, $selectTable, $arElement);
                }

                // Recurce for child categories
                if (!empty($arChildId)) {
                    foreach ($arChildId as $childId)
                        $this->deletePropertyWithChange($requestData, $childId['id'], $isChangeTitle, $isChangeOrder, $isChangeCategoryId);
                }

                break;
        }

    }

    /**
     * Delete properties array
     *
     * @param $arProperties
     * @param $requestData
     * @param $selectTable
     * @param $arElement
     */
    public function deleteArProperties($arProperties, $requestData, $selectTable, $arElement)
    {
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

                // Delete property values from value array
                unset($arProperties[$propGroup][$requestData['id']]);

                $selectTable->where('id', $arElement['id'])->update(['properties' => serialize($arProperties)]);
            }
        }
    }

    /**
     * Change properties title in elements
     *
     * @param $arProperties
     * @param $requestData
     * @param $selectTable
     * @param $arElement
     */
    public function changeProperties($arProperties, $requestData, $selectTable, $arElement)
    {
        foreach ($arProperties as $propGroup=>$arProperty) {
            if (key_exists($requestData['id'], $arProperty)) {
                if ($requestData['old_title'] != $requestData['title'])
                    $arProperties[$propGroup][$requestData['id']]['title'] = $requestData['title'];

                if ($requestData['old_order'] != $requestData['order'])
                    $arProperties[$propGroup][$requestData['id']]['order'] = $requestData['order'];
            }
            uasort($arProperties[$propGroup], ['self', 'sortArray']);
        }

        $selectTable->where('id', $arElement['id'])->update(['properties' => serialize($arProperties)]);
    }

    /**
     * Handle properties for public
     *
     * @param $items
     * @return mixed
     */
    public function handlePropertyForPublic($items)
    {
        if(!array_key_exists(0, $items))
            $items = [0 => $items];

        $arPropEnumId = [];
        $arPropEnum = [];
        $arPropEnumGroup =[];
        $arItemsId = [];
        $arItems = [];
        $arItemsGroup = [];

        foreach ($items as $keyItem => $item) {
            if (!empty($item['properties'])) {
                $items[$keyItem]['properties'] = unserialize($item['properties']);

                foreach ($items[$keyItem]['properties'] as $groupName => $propGroup) {

                    if (empty($propGroup))
                        unset($items[$keyItem]['properties'][$groupName]);

                    foreach ($propGroup as $propId => $property) {

                        if ($property['type'] == PROP_TYPE_IMG) {
                            $items[$keyItem]['properties'][$groupName][$propId]['value'] = $this->createPublicImgPath($property['value']);
                        }
                        elseif ($property['type'] == PROP_TYPE_LIST) {
                            // Create enum values array
                            array_walk_recursive($property['value'], function ($value, $key) use (&$arPropEnumId){
                                $arPropEnumId[] = (int)$value;
                            }, $arPropEnumId);
                        }
                        elseif ($property['type'] == PROP_TYPE_ITEM_LINK) {
                            // Create item ids array
                            array_walk_recursive($property['value'], function ($value, $key) use (&$arItemsId){
                                $arItemsId[] = (int)$value;
                            }, $arItemsId);
                        }
                        elseif ($property['type'] == PROP_TYPE_CATEGORY_LINK) {
                            $items[$keyItem]['properties'][$groupName][$propId]['arItem'] = $this->getLinkCategories($property['value']);
                        }
                    }
                }

            }
        }


        // Get enum propertyes
        if (!empty($arPropEnumId)) {
            $arPropEnum = PropEnum::whereIn('id', $arPropEnumId)->select('id', 'title', 'order', 'slug', 'prop_id')
                ->orderby('order', 'asc')->get()->toArray();

            // Group enum properties by prop_id
            foreach ($arPropEnum as $key => $propEnum) {
                $arPropEnumGroup[$propEnum['prop_id']][] = $propEnum;
            }
        }

        // Get items for item properties
        if (!empty($arItemsId)) {
            $arItems = $this->getLinkItems($arItemsId);

            // Group item link properties by id
            foreach ($arItems as $key => $arItem) {
                $arItemsGroup[$arItem['id']] = $arItem;
            }
        }

        // Fill properties values
        foreach ($items as $keyItem => $item) {
            if (!empty($item['properties'])) {

                foreach ($item['properties'] as $groupName => $propGroup) {
                    foreach ($propGroup as $propId => $property) {
                        if ($property['type'] == PROP_TYPE_LIST) {
                            if (array_key_exists($propId, $arPropEnumGroup))
                                $items[$keyItem]['properties'][$groupName][$propId]['arList'] = $arPropEnumGroup[$propId];
                        } elseif ($property['type'] == PROP_TYPE_ITEM_LINK) {
                            foreach ($property['value'] as $key => $valueItemId) {
                                if (array_key_exists($valueItemId, $arItemsGroup))
                                    $items[$keyItem]['properties'][$groupName][$propId]['arItem'][] = $arItemsGroup[$valueItemId];
                            }
                        }
                    }
                }

            }
        }

        return $items;
    }

    /**
     * Get link items for public
     *
     * @param $arItemId
     * @return array
     */
    public function getLinkItems($arItemId)
    {
        $arItems = [];

        $arItems = Item::with('category')->whereIn('id', $arItemId)
            ->orderby('order', 'asc')->orderby('updated_at', 'desc')
            ->select(['id', 'title', 'preview_img', 'properties', 'slug', 'is_product', 'category_id'])
            ->get()->toArray();

        foreach ($arItems as $key=>$item) {
            if (isset($item['preview_img']))
                $arItems[$key]['preview_img'] = $this->createPublicImgPath(unserialize($item['preview_img']));

            if (isset($item['properties']))
                $arItems[$key]['properties'] = unserialize($item['properties']);

            // Set href
            if ($item['category_id'] != 0) {
                if ($item['is_product'] == 1)
                    $arItems[$key]['slug'] = '/' . CATALOG_SLUG . '/' . $item['category']['slug'] . '/' . $item['slug'];
                elseif ($item['is_product'] == 0)
                    $arItems[$key]['slug'] = '/' . BLOG_SLUG . '/' . $item['category']['slug'] . '/' . $item['slug'];
            }
        }

        return $arItems;
    }

    /**
     * Get link categories
     *
     * @param $arCategoryId
     * @return mixed
     */
    public function getLinkCategories($arCategoryId)
    {
        $arCategories = [];

        $arCategories = Category::orderby('order', 'asc')->orderby('updated_at', 'desc');

        foreach ($arCategoryId as $categoryId)
            $arCategories = $arCategories->orWhere('id', $categoryId);

        $arCategories = $arCategories->select(['id', 'title', 'preview_img', 'properties', 'slug', 'catalog_section', 'parent_id'])->get()->toArray();

        foreach ($arCategories as $key=>$category) {
            if (isset($category['preview_img']))
                $arCategories[$key]['preview_img'] = $this->createPublicImgPath(unserialize($category['preview_img']));

            if (isset($category['properties']))
                $arCategories[$key]['properties'] = unserialize($category['properties']);

            // Set href
            if ($category['parent_id'] != 0) {
                if ($category['catalog_section'] == 1)
                    $arCategories[$key]['slug'] = '/' . CATALOG_SLUG . '/' . $category['slug'];
                elseif ($category['catalog_section'] == 0)
                    $arCategories[$key]['slug'] = '/' . BLOG_SLUG . '/' . $category['slug'];
            }
        }

        return $arCategories;
    }

    /**
     * @param $a
     * @param $b
     * @return bool
     */
    public static function sortArray($a, $b) {
        return ($a['order'] > $b['order']);
    }
}
