<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Menu;
use App\MenuItem;
use App\MenuType;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        MenuType::createTable();
//        Menu::createTable();
//        MenuItem::createTable();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::dropIfExists('menus');
//        Schema::dropIfExists('menu_types');
//        Schema::dropIfExists('menu_items');
    }
}
