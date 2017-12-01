<?php

namespace App;

trait CategoryTrait
{
    /**
     * Destroy with images and properties
     *
     * @param $selectTable
     */
    public function baseDestroy($selectTable)
    {
        // Delete preview images
        $this->deleteImgWithDestroy($selectTable, 'preview_img');

        // Delete download files
        $this->deleteFileWithDestroy($selectTable);

        // Delete properties
        $this->deletePropertyWithDestroy($selectTable);

        // Delete category
        $selectTable->destroy($selectTable->id);
    }

    /**
     * Destroy with children elements
     *
     * @param $selectTable
     * @param $parentIdField
     */
    public function recurceDestroy($selectTable, $parentIdField)
    {
        $childs = $selectTable->where($parentIdField, $selectTable->id)->get();
        $this->baseDestroy($selectTable);

        if (isset($childs)) {
            foreach ($childs as $child)
                $this->recurceDestroy($child, $parentIdField);
        }
    }
}
