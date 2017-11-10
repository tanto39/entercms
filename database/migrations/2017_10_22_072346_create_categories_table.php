<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('categories', function (Blueprint $table) {
//            $table->increments('id');
//            $table->integer('order')->nullable();
//            $table->string('title');
//            $table->string('preview_img')->nullable();
//            $table->text('meta_key')->nullable();
//            $table->text('meta_desc')->nullable();
//            $table->text('description')->nullable();
//            $table->text('full_content')->nullable();
//            $table->string('slug')->unique()->nullable();
//            $table->integer('parent_id')->nullable();
//            $table->tinyInteger('published')->nullable();
//            $table->integer('created_by')->nullable()->unsigned();
//            $table->integer('modify_by')->nullable()->unsigned();
//            $table->text('properties')->nullable();
//            $table->timestamps();
//
//            // Foreign keys
//            $table->foreign('created_by')->references('id')->on('users');
//            $table->foreign('modify_by')->references('id')->on('users');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::dropIfExists('categories');
    }
}
