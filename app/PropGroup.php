<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

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

    /**
     * Create table prop_groups
     */
    public static function createTable()
    {
        Schema::create('prop_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order')->nullable();
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->timestamps();
        });
    }
}
