<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            // === 収入 (Income) ===
            ['name' => '給与', 'transaction_type_id' => 1, 'created_at' => now()],
            ['name' => 'ボーナス', 'transaction_type_id' => 1, 'created_at' => now()],
            ['name' => '副収入', 'transaction_type_id' => 1, 'created_at' => now()],
            ['name' => '利息', 'transaction_type_id' => 1, 'created_at' => now()],
            ['name' => '投資収益', 'transaction_type_id' => 1, 'created_at' => now()],
            ['name' => '年金', 'transaction_type_id' => 1, 'created_at' => now()],
            ['name' => 'お小遣い', 'transaction_type_id' => 1, 'created_at' => now()],
            ['name' => '贈与', 'transaction_type_id' => 1, 'created_at' => now()],
            ['name' => '還付金', 'transaction_type_id' => 1, 'created_at' => now()],
            ['name' => 'その他収入', 'transaction_type_id' => 1, 'created_at' => now()],

            // === 支出 (Expense) ===
            ['name' => '家賃', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => 'ローン', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => '光熱費', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => '電気代', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => '水道代', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => 'ガス代', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => '通信費', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => '食費', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => '外食', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => '日用品', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => '医療費', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => '交通費', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => '教育費', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => '保険', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => '税金', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => '娯楽費', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => '旅行', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => '趣味', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => '衣服', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => '美容', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => 'ペット関連', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => 'プレゼント', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => '貯金', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => '投資', 'transaction_type_id' => 2, 'created_at' => now()],
            ['name' => '雑費', 'transaction_type_id' => 2, 'created_at' => now()],
        ]);
    }
}
