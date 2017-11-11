<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Group properties table
        Schema::create('prop_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order')->nullable();
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->timestamps();
        });

        // Properties table
        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order')->nullable();
            $table->string('title');
            $table->string('preview_img')->nullable();
            $table->text('meta_key')->nullable();
            $table->text('meta_desc')->nullable();
            $table->text('description')->nullable();
            $table->text('full_content')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->tinyInteger('published')->nullable();
            $table->string('type')->nullable(); // values: html, img, int, str, category_link, item_link
            $table->string('is_insert');        // values: Y, N
            $table->string('prop_kind');        // // values: category, item
            $table->integer('category_id')->nullable()->unsigned();
            $table->integer('group_id')->nullable()->unsigned();
            $table->timestamps();

            // Foreign keys
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('group_id')->references('id')->on('prop_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
        Schema::dropIfExists('prop_groups');
    }
}
