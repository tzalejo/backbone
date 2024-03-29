<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMunicipalitiesTable extends Migration
{
    public function up(): void
    {
        Schema::create('municipalities', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->unsignedBigInteger('federal_entity_id');
            $table->foreign('federal_entity_id')->references('id')->on('federal_entities');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipalities');
    }
}
