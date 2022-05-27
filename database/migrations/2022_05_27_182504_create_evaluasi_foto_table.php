<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluasiFotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluasi_foto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evaluasi_id');
            $table->foreign('evaluasi_id')->references('id')->on('evaluasi')->onDelete('cascade');
            $table->unsignedBigInteger('kriteria_id');
            $table->string('nama_kriteria', 200);
            $table->foreign('kriteria_id')->references('id')->on('kriteria')->onDelete('cascade');
            $table->string('foto', 200);
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
        Schema::dropIfExists('evaluasi_foto');
    }
}
