<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetugasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('petugas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_id');
            $table->unsignedBigInteger('province_code');
            $table->unsignedBigInteger('city_code');
            $table->unsignedBigInteger('district_code');
            $table->unsignedBigInteger('village_code');
            $table->string('jabatan', 200);
            $table->string('nomer_hp', 200);
            $table->boolean('flag_verif')->default(0);
            $table->boolean('flag_pakai')->default(0);
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
        Schema::dropIfExists('petugas');
    }
}
