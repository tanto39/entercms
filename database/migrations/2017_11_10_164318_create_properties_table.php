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
            $table->text('type')->nullable();
            $table->integer('category_id')->nullable()->unsigned();
            $table->timestamps();

            // Foreign keys
            $table->foreign('category_id')->references('id')->on('categories');
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
    }
}
