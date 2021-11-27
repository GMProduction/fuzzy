<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dudi_id')->unsigned();
            $table->string('name');
            $table->enum('percentage', ['rendah', 'cukup', 'tinggi'])->default('rendah');
            $table->foreign('dudi_id')->references('id')->on('tb_tempat_dudi');
            $table->timestamps();
        });

        Schema::create('rules_indicator', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('rule_id')->unsigned();
            $table->bigInteger('mapel_id')->unsigned();
            $table->enum('value', ['rendah', 'cukup', 'tinggi'])->default('rendah');
            $table->foreign('rule_id')->references('id')->on('rules');
            $table->foreign('mapel_id')->references('id')->on('tb_mapel');
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
        Schema::dropIfExists('rules_indicator');
        Schema::dropIfExists('rules');
    }
}
