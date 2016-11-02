<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevteamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devteam', function (Blueprint $table)
        {
            $table->engine = 'InnoDB';            
            $table->increments('id');
            $table->string('kod_ppd');
            $table->string('nama_kumpulan');
            $table->integer('ketua_kumpulan');
            $table->string('senarai_jtk');
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
        Schema::drop('devteam');
    }
}
