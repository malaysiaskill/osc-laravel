<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraFieldUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table)
        {
            $table->integer('gred');            # Gred Jawatan
            $table->integer('kod_jabatan');     # Kod Jabatan
            $table->integer('role');            # Role Pentadbir
            $table->integer('user_group');      # Kumpulan Pengguna
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table)
        {
            $table->dropColumn('gred');
            $table->dropColumn('kod_jabatan');
            $table->dropColumn('role');
            $table->dropColumn('user_group');
        });
    }
}
