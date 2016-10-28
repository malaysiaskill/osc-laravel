<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSekolahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sekolah', function (Blueprint $table)
        {
            $table->engine = 'InnoDB';
            
            $table->increments('id');
            $table->string('KODPPD');
            $table->string('KODSEKOLAH')->unique();
            $table->string('NAMASEKOLAH');
            $table->string('ALAMAT')->nullable();
            $table->integer('POSKOD')->nullable();
            $table->string('BANDAR')->nullable();
            $table->string('NOTELEFON')->nullable();
            $table->string('NOFAX')->nullable();
            $table->string('EMAIL')->nullable();
            $table->string('HOMEPAGE')->nullable();

            # Foreign Key
            $table->foreign('KODPPD')->references('KODPPD')->on('ppd')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sekolah');
    }
}
