<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SekolahTable extends Migration
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
            $table->string('IDSEKOLAH')->nullable();
            $table->string('KODSEKOLAH')->unique();
            $table->string('NAMASEKOLAH');
            $table->string('ALAMATLOKASISEKOLAH')->nullable();
            $table->string('BANDARLOKASISEKOLAH')->nullable();
            $table->integer('POSKODLOKASISEKOLAH')->nullable();
            $table->string('KODNEGERILOKASISEKOLAH')->nullable();
            $table->string('ALAMATSURAT')->nullable();
            $table->string('BANDARSURAT')->nullable();
            $table->integer('POSKODSURAT')->nullable();
            $table->string('KODNEGERISURAT')->nullable();
            $table->string('NOTELEFON')->nullable();
            $table->string('NOFAX')->nullable();
            $table->string('EMAIL')->nullable();
            $table->string('HOMEPAGE')->nullable();
            $table->string('KODSTATUSSEKOLAH')->nullable();
            $table->string('NOMBORPENDAFTARANSEKOLAH')->nullable();
            $table->string('KODLOKASIPENDAFTARAN')->nullable();
            $table->string('KODLOKASISEMASA')->nullable();
            $table->date('TARIKHTUBUHSEKOLAH')->nullable();
            $table->date('TARIKHSEKOLAHTUTUP')->nullable();
            $table->string('KODJENISSEKOLAH')->nullable();
            $table->string('GREDSEKOLAH')->nullable();
            $table->string('KODJENISBANTUAN')->nullable();
            $table->string('KODPERINGKAT')->nullable();
            $table->string('KODJENISMURID')->nullable();
            $table->string('KODBAHASAPENGANTAR')->nullable();
            $table->string('KODSEJARAHSEKOLAH')->nullable();
            $table->string('KODSESI')->nullable();
            $table->string('KODSUMBERAIR')->nullable();
            $table->string('KODSUMBERAIR2')->nullable();
            $table->string('KODSUMBERELEKTRIK')->nullable();
            $table->string('KODCATATAN')->nullable();
            $table->string('CATATANLAIN')->nullable();
            $table->string('BOLEHGUNABIT')->nullable();
            $table->string('JARAKELEKTRIK')->nullable();
            $table->string('KODNEGERIJPN')->nullable();
            $table->string('KODDAERAH')->nullable();
            $table->string('MUKIM')->nullable();
            $table->string('KODPARLIMEN')->nullable();
            $table->string('KODDUN')->nullable();
            $table->string('KODPPD')->nullable();
            $table->string('JARAKPPD')->nullable();
            $table->string('KODPKG')->nullable();
            $table->string('BANDARTERDEKAT')->nullable();
            $table->string('KODKERAJAANTEMPATAN')->nullable();
            $table->string('KODTUMPANG')->nullable();
            $table->string('KODSEKTUMPANG')->nullable();
            $table->string('KODSEKMENTERDEKAT')->nullable();
            $table->string('KODSEKRENTERDEKAT')->nullable();
            $table->string('JARAKKESEKMENTERDEKAT')->nullable();
            $table->string('JARAKKESEKRENTERDEKAT')->nullable();
            $table->string('BILOPTIMUMPELAJAR')->nullable();
            $table->string('BILKELASCANTUM')->nullable();
            $table->string('BILKELASINKLUSIF')->nullable();
            $table->string('BILKELASTERAPUNG')->nullable();
            $table->string('BILMURIDCANTUM')->nullable();
            $table->string('BILMURIDINKLUSIF')->nullable();
            $table->string('BILMURIDTERAPUNG')->nullable();
            $table->string('KODKANTINSEKOLAH')->nullable();
            $table->string('LUASRUANGMAKANKANTINSEK')->nullable();
            $table->string('KODJENISBEKALANMAKAN')->nullable();
            $table->string('KODJENISASRAMA')->nullable();
            $table->string('PENGHUNIASRAMA')->nullable();
            $table->string('OPTIMUMASRAMA')->nullable();
            $table->string('KODKANTINASRAMA')->nullable();
            $table->string('KODDAPURASRAMA')->nullable();
            $table->string('OPTIMUMDEWANMAKAN')->nullable();
            $table->string('RUMAHGURUBIT')->nullable();
            $table->string('ASRAMABIT')->nullable();
            $table->string('PTJ')->nullable();
            $table->string('IDPREMIS')->nullable();
            $table->string('KOORDINATXX')->nullable();
            $table->string('KOORDINATYY')->nullable();
            $table->string('COLLECTIONPERIOD"')->nullable();
            $table->string('DRAFTFLAG')->nullable();
            $table->string('KUTIPANDATA')->nullable();
            $table->string('LPSEKOLAH')->nullable();
            $table->date('TARIKHLPSEKOLAH')->nullable();
            $table->string('LASTMODIFIEDBY')->nullable();
            $table->date('LASTMODIFIEDDATE')->nullable();
            $table->string('CRC')->nullable();
            $table->string('JARAKBANDARTERDEKAT')->nullable();
            $table->string('KODSTATUSBEKALANELEKTRIK')->nullable();
            $table->string('KODJENISTELEKOMUNIKASI')->nullable();
            $table->string('KODPERKHIDMATANKESIHATAN')->nullable();
            $table->string('NAMAKLINIK')->nullable();
            $table->string('JARAKKLINIK')->nullable();
            $table->date('TARIKHMULAMENUMPANG')->nullable();
            $table->string('NOTELEFONBIMBIT')->nullable();
            $table->string('KODSTATUSBEKALANAIR"')->nullable();

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
