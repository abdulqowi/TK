<?php



use App\User;
use App\Receipt;
use App\UserDetail;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insertGetId([
            'parent_name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'role' => '1',
            'phone' =>'08112345678',
        ]);

        $faker = \Faker\Factory::create();  
        for($i = 0; $i < 51; $i++) {
            $id = User::insertGetId([
                'parent_name'      =>  $faker->name,
                'email'     =>  $faker->unique()->safeEmail,
                'password'  =>  Hash::make('password'),
                'phone' => Str::random(10),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $gender = ['L', 'P'];
            UserDetail::insert([
                'user_id' => $id,
                'student_name' => $faker ->name,
                'address' => Str::random(10),
                'mother'        => $faker ->name,
                'mother_phone'         =>random_int(0,666),
                'mother_email'         => $faker ->name,
                'mother_job'         => Str::random(5), 
                'mother_degree'    => Str::random(5),
                'father_job' =>     Str::random(5),
                'birthday' => Str::random(5),
                'birthplace' => Str::random(5),
                'father_degree' => Str::random(5),
                'gender' => $gender[array_rand($gender)],
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
        Artisan::call('passport:install');
    }
}

