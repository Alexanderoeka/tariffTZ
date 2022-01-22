<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Addresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses',function (Blueprint $table){

            $table->bigIncrements('id');
            $table->string('address',100)->unique();
            $table->string('house_fias_id')->nullable();
            $table->string('region_with_type')->nullable();
            $table->string('city_with_type')->nullable();
            $table->string('street_with_type')->nullable();
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
        Schema::dropIfExists('addresses');
    }
}
