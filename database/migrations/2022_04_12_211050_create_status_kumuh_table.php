<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusKumuhTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_kumuh', function (Blueprint $table) {
            $table->id();
            $table->string('nama',200);
            $table->string('warna',200);
            $table->string('icon', 200);
            $table->float('nilai_min', 8, 2);
            $table->float('nilai_max', 8, 2);
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
        Schema::dropIfExists('status_kumuh');
    }
}
