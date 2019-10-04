<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class Region extends Model
{
    /**
     * Fields white list
     */
    protected $fillable = [
        'title',
        'order',
        'slug',
        'regwhere',
    ];

    /**
     * Create table regions
     */
    public static function createTable()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order')->nullable();
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->string('regwhere')->nullable();
            $table->timestamps();
        });

        DB::insert('insert into regions (title, slug, regwhere) values (?, ?, ?)', ['Курск', 'kursk', 'Курске']);
        DB::insert('insert into regions (title, slug, regwhere) values (?, ?, ?)', ['Москва', 'msk', 'Москве']);
    }
}
