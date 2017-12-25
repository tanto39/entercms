<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class MenuType extends Model
{
    /**
     * Fields white list
     */
    protected $fillable = [
        'title',
        'order',
        'slug',
    ];

    /**
     * Create table menu_types
     */
    public static function createTable()
    {
        Schema::create('menu_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order')->nullable();
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->timestamps();
        });

        DB::insert('insert into menu_types (title, slug) values (?, ?)', ['Категория', 'category']);
        DB::insert('insert into menu_types (title, slug) values (?, ?)', ['Материал', 'material']);
        DB::insert('insert into menu_types (title, slug) values (?, ?)', ['Отзывы', 'reviews']);
        DB::insert('insert into menu_types (title, slug) values (?, ?)', ['Ссылка', 'link']);
    }
}
