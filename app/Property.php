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
        'type',           // values: html, img, file, int, str, category_link, item_link
        'is_insert',      // values: Y, N
        'prop_kind',      // values: category, item
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
            $table->integer('type')->nullable()->unsigned();; // values: html, img, file, int, str, category_link, item_link
            $table->string('is_insert')->default('Y');        // values: Y, N
            $table->integer('prop_kind')->nullable()->unsigned();;        // // values: category, item
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
