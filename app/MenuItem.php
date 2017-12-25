<?php

namespace App;

use App\Menu;
use App\MenuType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class MenuItem extends Model
{
    use AdminPanel;
    /**
     * Fields white list
     */
    protected $fillable = [
        'title',
        'order',
        'slug',
        'href',
        'type',
        'link_id',
        'parent_id',
        'menu',
        'show_child', // values: 1, 0
    ];

    /**
     * Get menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu() {
        return $this->belongsTo('App\Menu', 'menu');
    }

    /**
     * Create table menus
     */
    public static function createTable()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order')->nullable();
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->string('href')->nullable();
            $table->integer('type')->nullable()->unsigned();
            $table->integer('parent_id')->nullable()->default(0);
            $table->integer('menu')->nullable()->unsigned();
            $table->integer('link_id')->nullable();
            $table->tinyInteger('show_child')->nullable()->default(1);
            $table->timestamps();

            // Foreign keys
            $table->foreign('type')->references('id')->on('menu_types');
            $table->foreign('menu')->references('id')->on('menus');
        });
    }
}
