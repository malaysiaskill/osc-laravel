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
        DB::table('ppd')->insert(['KODJPN' => '08', 'KODPPD' => 'A010', 'PPD' => 'PEJABAT PENDIDIKAN DAERAH BATANG PADANG']);
        DB::table('ppd')->insert(['KODJPN' => '08', 'KODPPD' => 'A020', 'PPD' => 'PEJABAT PENDIDIKAN DAERAH MANJUNG']);
        DB::table('ppd')->insert(['KODJPN' => '08', 'KODPPD' => 'A040', 'PPD' => 'PEJABAT PENDIDIKAN DAERAH KRIAN']);
        DB::table('ppd')->insert(['KODJPN' => '08', 'KODPPD' => 'A050', 'PPD' => 'PEJABAT PENDIDIKAN DAERAH KUALA KANGSAR']);
        DB::table('ppd')->insert(['KODJPN' => '08', 'KODPPD' => 'A060', 'PPD' => 'PEJABAT PENDIDIKAN DAERAH HILIR PERAK']);
        DB::table('ppd')->insert(['KODJPN' => '08', 'KODPPD' => 'A070', 'PPD' => 'PEJABAT PENDIDIKAN DAERAH LARUT/MATANG/SELAMA']);
        DB::table('ppd')->insert(['KODJPN' => '08', 'KODPPD' => 'A080', 'PPD' => 'PEJABAT PENDIDIKAN DAERAH HULU PERAK']);
        DB::table('ppd')->insert(['KODJPN' => '08', 'KODPPD' => 'A090', 'PPD' => 'PEJABAT PENDIDIKAN DAERAH PERAK TENGAH']);
        DB::table('ppd')->insert(['KODJPN' => '08', 'KODPPD' => 'A100', 'PPD' => 'PEJABAT PENDIDIKAN DAERAH KINTA UTARA']);
        DB::table('ppd')->insert(['KODJPN' => '08', 'KODPPD' => 'A110', 'PPD' => 'PEJABAT PENDIDIKAN DAERAH KINTA SELATAN']);
    }
}
