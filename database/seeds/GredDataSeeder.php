<?php

use Illuminate\Database\Seeder;

class GredDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Juruteknik Komputer
        DB::table('gred')->insert(['gred' => 'FT19', 'nama_jawatan' => 'JURUTEKNIK KOMPUTER']);
        DB::table('gred')->insert(['gred' => 'FT22', 'nama_jawatan' => 'JURUTEKNIK KOMPUTER']);
        DB::table('gred')->insert(['gred' => 'FT26', 'nama_jawatan' => 'JURUTEKNIK KOMPUTER']);
        DB::table('gred')->insert(['gred' => 'FT28', 'nama_jawatan' => 'JURUTEKNIK KOMPUTER']);
        
        // Penolong Pegawai Teknologi Maklumat
        DB::table('gred')->insert(['gred' => 'FA29', 'nama_jawatan' => 'PENOLONG PEGAWAI TEKNOLOGI MAKLUMAT']);
        DB::table('gred')->insert(['gred' => 'FA32', 'nama_jawatan' => 'PENOLONG PEGAWAI TEKNOLOGI MAKLUMAT']);
        DB::table('gred')->insert(['gred' => 'FA38', 'nama_jawatan' => 'PENOLONG PEGAWAI TEKNOLOGI MAKLUMAT']);
        DB::table('gred')->insert(['gred' => 'FA40', 'nama_jawatan' => 'PENOLONG PEGAWAI TEKNOLOGI MAKLUMAT']);

        // Pegawai Teknologi Maklumat
        DB::table('gred')->insert(['gred' => 'F41', 'nama_jawatan' => 'PEGAWAI TEKNOLOGI MAKLUMAT']);
        DB::table('gred')->insert(['gred' => 'F44', 'nama_jawatan' => 'PEGAWAI TEKNOLOGI MAKLUMAT']);
        DB::table('gred')->insert(['gred' => 'F48', 'nama_jawatan' => 'PEGAWAI TEKNOLOGI MAKLUMAT']);
        DB::table('gred')->insert(['gred' => 'F52', 'nama_jawatan' => 'PEGAWAI TEKNOLOGI MAKLUMAT']);
        DB::table('gred')->insert(['gred' => 'F54', 'nama_jawatan' => 'PEGAWAI TEKNOLOGI MAKLUMAT']);
    }
}
