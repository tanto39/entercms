<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class StatusOrder extends Model
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
     * Protected fields
     */
    protected $guarded = [
        'id'
    ];

    /**
     * Create table status_orders
     */
    public static function createTable()
    {
        Schema::create('status_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order')->nullable();
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->timestamps();
        });

        DB::insert('insert into status_orders (title, slug) values (?, ?)', ['Новый', 'new']);
        DB::insert('insert into status_orders (title, slug) values (?, ?)', ['Принят', 'get']);
        DB::insert('insert into status_orders (title, slug) values (?, ?)', ['Оплачен', 'pay']);
        DB::insert('insert into status_orders (title, slug) values (?, ?)', ['Отгружен', 'shipped']);
        DB::insert('insert into status_orders (title, slug) values (?, ?)', ['Закрыт', 'close']);
    }
}
