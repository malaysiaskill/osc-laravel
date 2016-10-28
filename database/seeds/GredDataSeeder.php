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
        DB::table('gred')->insert(['GRED' => 'FT19', 'NAMAJAWATAN' => 'JURUTEKNIK KOMPUTER']);
        DB::table('gred')->insert(['GRED' => 'FT22', 'NAMAJAWATAN' => 'JURUTEKNIK KOMPUTER']);
        DB::table('gred')->insert(['GRED' => 'FT26', 'NAMAJAWATAN' => 'JURUTEKNIK KOMPUTER']);
        DB::table('gred')->insert(['GRED' => 'FT28', 'NAMAJAWATAN' => 'JURUTEKNIK KOMPUTER']);
        
        // Penolong Pegawai Teknologi Maklumat
        DB::table('gred')->insert(['GRED' => 'FA29', 'NAMAJAWATAN' => 'PENOLONG PEGAWAI TEKNOLOGI MAKLUMAT']);
        DB::table('gred')->insert(['GRED' => 'FA32', 'NAMAJAWATAN' => 'PENOLONG PEGAWAI TEKNOLOGI MAKLUMAT']);
        DB::table('gred')->insert(['GRED' => 'FA38', 'NAMAJAWATAN' => 'PENOLONG PEGAWAI TEKNOLOGI MAKLUMAT']);
        DB::table('gred')->insert(['GRED' => 'FA40', 'NAMAJAWATAN' => 'PENOLONG PEGAWAI TEKNOLOGI MAKLUMAT']);

        // Pegawai Teknologi Maklumat
        DB::table('gred')->insert(['GRED' => 'F41', 'NAMAJAWATAN' => 'PEGAWAI TEKNOLOGI MAKLUMAT']);
        DB::table('gred')->insert(['GRED' => 'F44', 'NAMAJAWATAN' => 'PEGAWAI TEKNOLOGI MAKLUMAT']);
        DB::table('gred')->insert(['GRED' => 'F48', 'NAMAJAWATAN' => 'PEGAWAI TEKNOLOGI MAKLUMAT']);
        DB::table('gred')->insert(['GRED' => 'F52', 'NAMAJAWATAN' => 'PEGAWAI TEKNOLOGI MAKLUMAT']);
        DB::table('gred')->insert(['GRED' => 'F54', 'NAMAJAWATAN' => 'PEGAWAI TEKNOLOGI MAKLUMAT']);
    }
}
