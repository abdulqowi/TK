<?php

use App\Receipt;
use Illuminate\Database\Seeder;

class ReceiptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            Receipt::create([
            'user_id' => rand(1,51),
            'product_details_id' => rand(1,51),
            'course_id' => rand(1,5),
            'total_price' =>rand(1,5),
            ]);
    }
}
