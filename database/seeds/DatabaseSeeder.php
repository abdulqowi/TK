<?php

use App\Receipt;
use Illuminate\Database\Seeder;
use Receipt as GlobalReceipt;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            Course::class,
            UserSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
