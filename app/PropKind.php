<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class PropKind extends Model
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
        Schema::create('prop_kinds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order')->nullable();
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->timestamps();
        });

        DB::insert('insert into prop_kinds (title, slug) values (?, ?)', ['Категория', 'category']);
        DB::insert('insert into prop_kinds (title, slug) values (?, ?)', ['Материал', 'item']);
    }
}
