<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class PropType extends Model
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
     * Create table prop_groups
     */
    public static function createTable()
    {
        Schema::create('prop_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order')->nullable();
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->timestamps();
        });

        DB::insert('insert into prop_types (title, slug) values (?, ?)', ['Число', 'int']);
        DB::insert('insert into prop_types (title, slug) values (?, ?)', ['Строка', 'str']);
        DB::insert('insert into prop_types (title, slug) values (?, ?)', ['Изображение', 'img']);
        DB::insert('insert into prop_types (title, slug) values (?, ?)', ['Файл', 'file']);
        DB::insert('insert into prop_types (title, slug) values (?, ?)', ['HTML', 'html']);
        DB::insert('insert into prop_types (title, slug) values (?, ?)', ['Список', 'list']);
        DB::insert('insert into prop_types (title, slug) values (?, ?)', ['Привязка к категории', 'category_link']);
        DB::insert('insert into prop_types (title, slug) values (?, ?)', ['Привязка к материалу', 'item_link']);
    }
}
