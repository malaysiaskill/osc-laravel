<?php

use Illuminate\Database\Seeder;

class ppd_data extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // PEJABAT PENDIDIKAN DAERAH MANJUNG
        DB::table('ppd')->insert(['KODJPN' => '08', 'KODPPD' => 'A020', 'PPD' => 'PEJABAT PENDIDIKAN DAERAH MANJUNG']);
    }
}
