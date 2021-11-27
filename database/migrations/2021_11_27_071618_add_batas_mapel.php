<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBatasMapel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_mapel_indikator', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_mapel')->unsigned();
            $table->enum('indikator', ['rendah', 'cukup', 'tinggi'])->default('rendah');
            $table->integer('bawah')->default(0);
            $table->integer('tengah')->default(0);
            $table->integer('atas')->default(0);
            $table->foreign('id_mapel')->references('id')->on('tb_mapel');
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
        Schema::dropIfExists('tb_mapel_indikator');
    }
}
