<?php

use Illuminate\Database\Seeder;

class PPDDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // PEJABAT PENDIDIKAN DAERAH
        DB::table('ppd')->insert(['kod_jpn' => '08', 'kod_ppd' => 'A010', 'ppd' => 'PEJABAT PENDIDIKAN DAERAH BATANG PADANG']);
        DB::table('ppd')->insert(['kod_jpn' => '08', 'kod_ppd' => 'A020', 'ppd' => 'PEJABAT PENDIDIKAN DAERAH MANJUNG']);
        DB::table('ppd')->insert(['kod_jpn' => '08', 'kod_ppd' => 'A040', 'ppd' => 'PEJABAT PENDIDIKAN DAERAH KRIAN']);
        DB::table('ppd')->insert(['kod_jpn' => '08', 'kod_ppd' => 'A050', 'ppd' => 'PEJABAT PENDIDIKAN DAERAH KUALA KANGSAR']);
        DB::table('ppd')->insert(['kod_jpn' => '08', 'kod_ppd' => 'A060', 'ppd' => 'PEJABAT PENDIDIKAN DAERAH HILIR PERAK']);
        DB::table('ppd')->insert(['kod_jpn' => '08', 'kod_ppd' => 'A070', 'ppd' => 'PEJABAT PENDIDIKAN DAERAH LARUT/MATANG/SELAMA']);
        DB::table('ppd')->insert(['kod_jpn' => '08', 'kod_ppd' => 'A080', 'ppd' => 'PEJABAT PENDIDIKAN DAERAH HULU PERAK']);
        DB::table('ppd')->insert(['kod_jpn' => '08', 'kod_ppd' => 'A090', 'ppd' => 'PEJABAT PENDIDIKAN DAERAH PERAK TENGAH']);
        DB::table('ppd')->insert(['kod_jpn' => '08', 'kod_ppd' => 'A100', 'ppd' => 'PEJABAT PENDIDIKAN DAERAH KINTA UTARA']);
        DB::table('ppd')->insert(['kod_jpn' => '08', 'kod_ppd' => 'A110', 'ppd' => 'PEJABAT PENDIDIKAN DAERAH KINTA SELATAN']);
    }
}
