<?php

use Illuminate\Database\Seeder;

class UsersDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin User
        DB::table('users')->insert(
            [
                'name' => 'Pentadbir Sistem',
                'email' => 'admin@domain.com',
                'password' => '$2y$10$VzArNThE8p3lScLDKdjfj.C0cemY9IJmmYgXUgP8TTdUseMqR.lry',
                'remember_token' => '',
                'role' => 'super-admin',
                'gred' => '1',
                'kod_jabatan' => '1',
                'created_at' => DB::raw('NOW()'),
                'updated_at' => DB::raw('NOW()')
            ]
        );
    }
}
