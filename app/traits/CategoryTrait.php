<?php

namespace App;

use App\Item;

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
     * Delete item
     *
     * @param $selectTable
     */
    public function itemDestroy($selectTable)
    {
        $this->baseDestroy($selectTable);
    }

    /**
     * Destroy with children elements
     *
     * @param $selectTable
     */
    public function recurceDestroy($selectTable)
    {
        $childs = $selectTable->where('parent_id', $selectTable->id)->get();

        $items = new Item();
        $items = $items->where('category_id', $selectTable->id)->get();

        // Delete items
        if (isset($items)) {
            foreach ($items as $item)
                $this->ItemDestroy($item);
        }

        // Delete category
        $this->baseDestroy($selectTable);

        if (isset($childs)) {
            foreach ($childs as $child)
                $this->recurceDestroy($child);
        }
    }
}
