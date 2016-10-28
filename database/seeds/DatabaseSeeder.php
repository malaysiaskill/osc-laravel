<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(roles_data::class);
        $this->call(gred_data::class);
        $this->call(jpn_data::class);
        $this->call(ppd_data::class);
        $this->call(sekolah_data::class);
    }
}