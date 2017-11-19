<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class PropEnum extends Model
{
    use AdminPanel;
    /**
     * Fields white list
     */
    protected $fillable = [
        'title',
        'order',
        'slug',
        'prop_id'
    ];

    /**
     * Create property enumeration table (for keeping property values of "list" type)
     */
    public static function createTable()
    {
        Schema::create('prop_enums', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order')->nullable();
            $table->string('title');
            $table->text('slug')->nullable();
            $table->integer('prop_id')->nullable()->unsigned();
            $table->timestamps();

            // Foreign keys
            $table->foreign('prop_id')->references('id')->on('properties');
        });
    }
}
