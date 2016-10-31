<?php

use Illuminate\Database\Seeder;

class RolesDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Roles
        DB::table('roles')->insert(['role' => 'super-admin', 'role_name' => 'Super Administrator']);
        DB::table('roles')->insert(['role' => 'admin', 'role_name' => 'Administrator']);
        DB::table('roles')->insert(['role' => 'monitor', 'role_name' => 'Monitoring User (View Only)']);
        DB::table('roles')->insert(['role' => 'jpn', 'role_name' => 'JPN User']);
        DB::table('roles')->insert(['role' => 'ppd', 'role_name' => 'PPD User']);
        DB::table('roles')->insert(['role' => 'user', 'role_name' => 'Normal User']);
    }
}
