<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GredTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gred', function (Blueprint $table)
        {
            $table->engine = 'InnoDB';
            
            $table->increments('id');
            $table->string('GRED')->unique();
            $table->string('NAMAJAWATAN');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('gred');
    }
}
