<?php

use Illuminate\Database\Seeder;

class roles_data extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ROLES | PENTADBIR
        DB::table('roles')->insert(['KODROLE' => '001', 'ROLE' => 'SUPER ADMINISTRATOR']);
        DB::table('roles')->insert(['KODROLE' => '002', 'ROLE' => 'ADMINISTRATOR']);
        DB::table('roles')->insert(['KODROLE' => '003', 'ROLE' => 'MONITORING USER']);
        DB::table('roles')->insert(['KODROLE' => '004', 'ROLE' => 'USER']);
    }
}
