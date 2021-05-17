<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('nick_name')->nullable();
            $table->string('reg_number')->unique()->nullable();
            $table->string('plate_number')->unique()->nullable();
            $table->unSignedBigInteger('color_of_plate_id');
            $table->foreign('color_of_plate_id')->references('id')->on('color_of_plates')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->unSignedBigInteger('plate_source_id');
            $table->foreign('plate_source_id')->references('id')->on('plate_sources')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->unSignedBigInteger('plate_category_id');
            $table->foreign('plate_category_id')->references('id')->on('plate_categories')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->unSignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
