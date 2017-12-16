<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class Order extends Model
{
    use AdminPanel;

    /**
     * Fields white list
     */
    protected $fillable = [
        'title',
        'order',
        'price',
        'count',
        'full_content',
        'item_id',
        'created_by',
        'status_order',
    ];

    /**
     * Protected fields
     */
    protected $guarded = [
        'id'
    ];

    /**
     * Create order table
     */
    public static function createTable()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order')->nullable();
            $table->integer('price')->nullable();
            $table->text('title');
            $table->text('full_content')->nullable();
            $table->integer('created_by')->nullable()->unsigned();
            $table->integer('modify_by')->nullable()->unsigned();
            $table->integer('status_order')->nullable()->unsigned();
            $table->timestamps();

            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('modify_by')->references('id')->on('users');
            $table->foreign('status_order')->references('id')->on('status_orders');
        });
    }
}
