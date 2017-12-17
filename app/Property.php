<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class Property extends Model
{
    use AdminPanel;

    /**
     * Fields white list
     */
    protected $fillable = [
        'title',
        'order',
        'slug',
        'type',           // values: html, img, file, int, str, list, category_link, item_link
        'is_insert',      // values: 1, 0
        'prop_kind',      // values: category, item
        'category_id',
        'group_id',
        'smart_filter',   // values: 1, 0
        'default'
    ];

    /**
     * Protected fields
     */
    protected $guarded = [
        'id'
    ];

    /**
     * Create property table
     */
    public static function createTable()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order')->nullable();
            $table->string('title');
            $table->text('slug')->nullable();
            $table->text('default')->nullable();
            $table->integer('type')->nullable()->unsigned(); // values: html, img, file, int, str, list, category_link, item_link
            $table->tinyInteger('is_insert')->default(1);        // values: 1, 0
            $table->tinyInteger('smart_filter')->nullable()->default(1);        // values: 1, 0
            $table->integer('prop_kind')->nullable()->unsigned(); // values: category, item
            $table->integer('category_id')->nullable()->unsigned();
            $table->integer('group_id')->nullable()->unsigned();
            $table->timestamps();

            // Foreign keys
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('group_id')->references('id')->on('prop_groups');
            $table->foreign('prop_kind')->references('id')->on('prop_kinds');
            $table->foreign('type')->references('id')->on('prop_types');
        });
    }
}
