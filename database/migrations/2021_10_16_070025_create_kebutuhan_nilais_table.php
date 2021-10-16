<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKebutuhanNilaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_kebutuhan_nilai', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_mapel')->unsigned()->nullable(true);
            $table->foreign('id_mapel')->references('id')->on('tb_mapel');
            $table->bigInteger('id_dudi')->unsigned()->nullable(true);
            $table->foreign('id_dudi')->references('id')->on('tb_user');
            $table->float('nilai');
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
        Schema::dropIfExists('tb_kebutuhan_nilai');
    }
}
