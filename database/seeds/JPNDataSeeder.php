<?php

use Illuminate\Database\Seeder;

class JPNDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // JPN
        DB::table('jpn')->insert(['KODNEGERI' => '08', 'KODJPN' => '08', 'JPN' => 'JABATAN PENDIDIKAN NEGERI PERAK']);
    }
}
