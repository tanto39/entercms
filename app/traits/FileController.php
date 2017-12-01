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
        $arProps = unserialize($strProps);

        foreach ($arProps as $key=>$arProp) {
            if ($arProp['type'] == PROP_TYPE_FILE)
                unset($arProps[$key]);
        }

        $strProps = serialize($arProps);
        $selectTable->where('id', $selectTable->id)->update(['properties' => $strProps]);

        $filePath = public_path($directory . $file);
        $this->deleteFileFromServer($filePath);
    }

    /**
     * Delete file from server
     *
     * @param $file
     */
    public function deleteFileFromServer($file)
    {
        $sem = sem_get(1);
        if ( sem_acquire($sem) && file_exists($file) ) @unlink($file);
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
        $obImage = $selectTable->select(['id', 'properties'])->where('id', $selectTable->id)->get();
        $arProps = unserialize($obImage->pluck('properties')[0]);

        if (count($arProps) > 0) {
            foreach ($arProps as $key=>$arProp) {
                if ($arProp['type'] == PROP_TYPE_FILE) {
                    $this->deletePropFile($arProp['value'], $selectTable, $directory);
                }
            }
        }
    }
}