<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for     ($i = 0; $i <51; $i++){
            DB::table('product_details',)->insert([
                'product_list' =>random_int(0,666),
                'total_price' =>Str::random(6),
            ]);
        };
    }
}
