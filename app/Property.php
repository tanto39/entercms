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
        'published',
        'title',
        'preview_img',
        'order',
        'slug',
        'meta_key',
        'meta_desc',
        'description',
        'full_content',
        'type',           // values: html, img, int, str, category_link, item_link
        'is_insert',      // values: Y, N
        'prop_kind',       // values: category, item
        'category_id',
        'group_id'
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
            $table->string('type')->nullable(); // values: html, img, file, int, str, category_link, item_link
            $table->string('is_insert');        // values: Y, N
            $table->string('prop_kind');        // // values: category, item
            $table->integer('category_id')->nullable()->unsigned();
            $table->integer('group_id')->nullable()->unsigned();
            $table->timestamps();

            // Foreign keys
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('group_id')->references('id')->on('prop_groups');
        });
    }
}
