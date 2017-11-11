<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropGroup extends Model
{
    use AdminPanel;
    /**
     * Fields white list
     */
    protected $fillable = [
        'title',
        'order',
        'slug',
    ];
}
