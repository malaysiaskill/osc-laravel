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
        DB::table('jpn')->insert(['kod_negeri' => '08', 'kod_jpn' => '08', 'jpn' => 'JABATAN PENDIDIKAN NEGERI PERAK']);
    }
}
