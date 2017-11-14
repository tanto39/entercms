<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\PropGroup;
use App\Property;
use App\PropType;
use App\PropKind;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Prop group table
        PropGroup::createTable();

        // Prop types table
        PropType::createTable();

        // Prop kinds table
        PropKind::createTable();

        // Properties table
        Property::createTable();
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
        Schema::dropIfExists('prop_types');
        Schema::dropIfExists('prop_kinds');
    }
}
