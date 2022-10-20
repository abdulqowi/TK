<?php


use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for     ($i = 0; $i <5; $i++){
            DB::table('courses',)->insert([
                'course_name' =>Str::random(6),
                'total_price' =>Str::random(6),
                'created_at' =>date('Y-m-d H:i:s'),
            ]);
        };
    }
}