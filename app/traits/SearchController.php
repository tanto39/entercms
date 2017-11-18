<?php

namespace App;

use Illuminate\Http\Request;

trait SearchController
{
    // Search properties
    public $searchText = '';

    /**
     * Search by title
     *
     * @param Request $request
     * @param $selectTable
     * @return mixed
     */
    public function searchByTitle(Request $request, $selectTable)
    {
        $this->searchText = $request->get('searchText');

        if(!empty($this->searchText))
            $selectTable = $selectTable->where('title', 'like', "%{$this->searchText}%");

        return $selectTable;
    }
}
