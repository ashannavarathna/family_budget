<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionTypesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('transaction_types')->insert([
            ['name' => '収入', 'created_at' => now()],
            ['name' => '支出', 'created_at' => now()],
        ]);
    }
}
