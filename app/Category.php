<?php

namespace App;

use App\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

class Category extends Model
{
    use AdminPanel;

    /**
     * Fields white list
     */
    protected $fillable = [
        'published', // values: 1, 0
        'catalog_section', // values: 1, 0
        'title',
        'order',
        'preview_img',
        'slug',
        'meta_key',
        'meta_desc',
        'description',
        'full_content',
        'created_by',
        'modify_by',
        'parent_id',
        'properties'
    ];

    /**
     * Get children category
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children() {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items() {
        return $this->hasMany('App\Item', 'category_id');
    }

    /**
     * Create table categories
     */
    public static function createTable()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order')->nullable();
            $table->string('title');
            $table->text('preview_img')->nullable();
            $table->text('meta_key')->nullable();
            $table->text('meta_desc')->nullable();
            $table->text('description')->nullable();
            $table->text('full_content')->nullable();
            $table->text('slug')->nullable();
            $table->integer('parent_id')->nullable();
            $table->tinyInteger('published')->nullable();
            $table->tinyInteger('catalog_section')->nullable()->default(0);
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
