<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePilihanJawabanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pilihan_jawaban', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sukriteria_id');
            $table->foreign('subkriteria_id')->references('id')->on('subkriteria')->onDelete('cascade');
            $table->string('jawaban', 200);
            $table->string('skor', 10);
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
        Schema::dropIfExists('pilihan_jawaban');
    }
}
