<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('province_code');
            $table->unsignedBigInteger('city_code');
            $table->unsignedBigInteger('district_code');
            $table->unsignedBigInteger('village_code');
            $table->unsignedBigInteger('status_id')->nullable();
            $table->foreign('status_id')->references('id')->on('status_kumuh')->onDelete('cascade');
            $table->string('tahun',200);
            $table->string('luas_kawasan',200);
            $table->string('luas_kumuh',200);
            $table->string('lingkungan', 200);
            $table->string('latitude',200);
            $table->string('longitude', 200);
            $table->string('gambar_delinasi', 200);
            $table->softDeletes();
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
        Schema::dropIfExists('evaluasi');
    }
}
