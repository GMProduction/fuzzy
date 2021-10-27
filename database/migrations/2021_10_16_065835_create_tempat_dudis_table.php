<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempatDudisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_tempat_dudi', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_user')->unsigned()->nullable(true);
            $table->foreign('id_user')->references('id')->on('tb_user');
            $table->string('nama');
            $table->text('alamat');
            $table->text('foto')->nullable(true)->default(null);
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
        Schema::dropIfExists('tb_tempat_dudi');
    }
}
