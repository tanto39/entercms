<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class Item extends Model
{
    use AdminPanel;

    /**
     * Fields white list
     */
    protected $fillable = [
        'published',
        'title',
        'order',
        'is_product',
        'preview_img',
        'rating',
        'slug',
        'meta_key',
        'meta_desc',
        'description',
        'full_content',
        'created_by',
        'modify_by',
        'category_id',
        'properties'
    ];

    /**
     * Get reviews
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews() {
        return $this->hasMany('App\Review', 'item_id');
    }

    /**
     * Get category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category() {
        return $this->belongsTo('App\Category', 'category_id');
    }

    /**
     * Create table categories
     */
    public static function createTable()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order')->nullable();
            $table->integer('rating')->nullable();
            $table->tinyInteger('is_product')->nullable()->default(0);
            $table->string('title');
            $table->text('preview_img')->nullable();
            $table->text('meta_key')->nullable();
            $table->text('meta_desc')->nullable();
            $table->text('description')->nullable();
            $table->text('full_content')->nullable();
            $table->text('slug')->nullable();
            $table->integer('category_id')->nullable();
            $table->tinyInteger('published')->nullable();
            $table->integer('created_by')->nullable()->unsigned();
            $table->integer('modify_by')->nullable()->unsigned();
            $table->text('properties')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('modify_by')->references('id')->on('users');
        });
    }
}
