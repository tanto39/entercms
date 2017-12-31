<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class Review extends Model
{
    use AdminPanel;

    /**
     * Fields white list
     */
    protected $fillable = [
        'title',
        'order',
        'published', // values: 1, 0
        'slug',
        'rating',
        'full_content',
        'item_id',
        'parent_id',
        'author_name'
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
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order')->nullable();
            $table->tinyInteger('published')->nullable()->default(0);
            $table->integer('rating')->nullable();
            $table->string('title');
            $table->string('author_name')->nullable();
            $table->text('slug')->nullable();
            $table->text('full_content')->nullable();
            $table->integer('item_id')->nullable()->unsigned();
            $table->integer('parent_id')->nullable();
            $table->integer('created_by')->nullable()->unsigned();
            $table->integer('modify_by')->nullable()->unsigned();
            $table->timestamps();

            // Foreign keys
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('modify_by')->references('id')->on('users');
        });
    }
}
