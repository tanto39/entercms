<?php

namespace App;

use Illuminate\Http\Request;

trait FileController
{
    /**
     * Load file on server
     *
     * @param $file
     * @param string $directory
     * @return bool|string
     */
    public function LoadFile($file, $directory = FILE_LOAD_PATH)
    {
        $fileName = "";

        $fileExtension = $file->getClientOriginalExtension();

        // Validate
        if (
            (($fileExtension != 'jpg') &&
                ($fileExtension != 'jpeg') &&
                ($fileExtension != 'png') &&
                ($fileExtension != 'pdf') &&
                ($fileExtension != 'doc') &&
                ($fileExtension != 'docx') &&
                ($fileExtension != 'xlsx') &&
                ($fileExtension != 'xls')) || ($file->getSize()) > 3000000
        )
            return false;

        // Move file
        $time = time();

        $fileName = str_replace('.', '-', $file->getClientOriginalName())
                . $time .'.'.$fileExtension;

        $file->move($directory, $fileName);

        return $fileName;
    }

    /**
     * Delete file
     *
     * @param $file
     * @param string $directory
     */
    public function deletePropFile($file, $selectTable, $directory = FILE_LOAD_PATH)
    {
        $strProps = $selectTable->select(['id', 'properties'])->where('id', $selectTable->id)->get()->toArray()[0]['properties'];
        $arPropsGrous = unserialize($strProps);

        foreach ($arPropsGrous as $groupName => $arProps) {
            foreach ($arProps as $key => $arProp) {
                if ($arProp['type'] == PROP_TYPE_FILE)
                    unset($arPropsGrous[$groupName][$key]);
            }
        }

        $strProps = serialize($arPropsGrous);
        $selectTable->where('id', $selectTable->id)->update(['properties' => $strProps]);

        $this->deleteFileFromServer($file, $directory);
    }

    /**
     * Delete file from server
     *
     * @param $file
     * @param $directory
     */
    public function deleteFileFromServer($file, $directory = FILE_LOAD_PATH)
    {
        $filePath = public_path($directory . $file);

        $sem = sem_get(1);
        if ( sem_acquire($sem) && file_exists($filePath) ) @unlink($filePath);
        sem_remove($sem);
    }

    /**
     * Delete files with destroy
     *
     * @param $selectTable
     * @param string $directory
     */
    public function deleteFileWithDestroy($selectTable, $directory = FILE_LOAD_PATH)
    {
        $arPropsGrous = [];

        $obImage = $selectTable->select(['id', 'properties'])->where('id', $selectTable->id)->get();
        $arPropsGrous = unserialize($obImage->pluck('properties')[0]);

        if ($arPropsGrous && count($arPropsGrous) > 0) {
            foreach ($arPropsGrous as $groupName => $arProps) {
                foreach ($arProps as $key => $arProp) {
                    if ($arProp['type'] == PROP_TYPE_FILE) {
                        $this->deletePropFile($arProp['value'], $selectTable, $directory);
                    }
                }
            }
        }

    }
}
