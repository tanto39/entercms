<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class Delivery extends Model
{
    use AdminPanel;

    /**
     * Fields white list
     */
    protected $fillable = [
        'published',
        'title',
        'order',
        'price',
        'full_content',
        'created_by',
    ];

    /**
     * Protected fields
     */
    protected $guarded = [
        'id'
    ];

    /**
     * Create delivery table
     */
    public static function createTable()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('published')->nullable();
            $table->integer('order')->nullable();
            $table->integer('price')->nullable();
            $table->text('title')->nullable();
            $table->text('full_content')->nullable();
            $table->integer('created_by')->nullable()->unsigned();
            $table->integer('modify_by')->nullable()->unsigned();
            $table->timestamps();

            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('modify_by')->references('id')->on('users');
        });
    }
}
