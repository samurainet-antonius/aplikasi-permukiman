<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluasiDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluasi_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evaluasi_id');
            $table->foreign('evaluasi_id')->references('id')->on('evaluasi')->onDelete('cascade');
            $table->unsignedBigInteger('kriteria_id');
            $table->string('nama_kriteria',200);
            $table->foreign('kriteria_id')->references('id')->on('kriteria')->onDelete('cascade');
            $table->unsignedBigInteger('subkriteria_id');
            $table->string('nama_subkriteria',200);
            $table->foreign('subkriteria_id')->references('id')->on('subkriteria')->onDelete('cascade');
            $table->longText('jawaban')->nullable();
            $table->float('skor', 8, 2)->nullable();
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
        Schema::dropIfExists('evaluasi_detail');
    }
}
