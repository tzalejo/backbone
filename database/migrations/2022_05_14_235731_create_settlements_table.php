<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settlements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key');
            $table->string('zone_type');

            $table->unsignedBigInteger('zip_code_id');
            $table->unsignedBigInteger('settlement_type_id');
            $table->unsignedBigInteger('municipality_id');

            $table->foreign('zip_code_id')->references('id')->on('zip_codes');
            $table->foreign('settlement_type_id')->references('id')->on('settlement_types');
            $table->foreign('municipality_id')->references('id')->on('municipalities');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settlements');
    }
}
