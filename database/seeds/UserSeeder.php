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
        $faker = \Faker\Factory::create();  
        for($i = 0; $i < 51; $i++) {
            $id = User::insertGetId([
                'parent_name'      =>  $faker->name,
                'email'     =>  $faker->unique()->safeEmail,
                'password'  =>  Hash::make('password'),
                'phone' => Str::random(10),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $sex = ['L', 'P'];
            UserDetail::insert([
                'user_id' => $id,
                'student_name' => $faker ->name,
                'address' => Str::random(10),
                'sex' => $sex[array_rand($sex)],
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
        }
        Artisan::call('passport:install');
    }
}

