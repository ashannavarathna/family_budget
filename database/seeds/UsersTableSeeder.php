<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'ruka',
            'email' => 'ruka@example.com',
            'password' => Hash::make('12345'),
            'role_id' => 1, // admin
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
