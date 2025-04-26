<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //Initialize Faker instance for generating realistic fake data
        $faker = Faker::create();

        // Initialize empty array to store user data structures
        $users = [];

        //Loop to create 10 unique user entries
        for ($i = 0; $i < 10; $i++){ 
            $FirstName = $faker->firstName();
            $LastName = $faker->lastName();
            $users[] = [
                'UserID' => $i + 1,  // Start at 1
                'FirstName' => $FirstName,
                'MiddleName' => $faker->lastName(), // Using firstName for middle name
                'LastName' => $LastName,
                'Username' => strtolower( $FirstName . '.' . $LastName),
                'Password' => Hash::make('password123'),
                'CreatedDate' => Carbon::now(),
                'UpdatedDate' => Carbon::now(),
                'DeletedDate' => null,
                'UpdatedByID' => null
            ];
        }
        
        // Perform bulk insert of all user records into database
        DB::connection('mysql')->table('userlist')->insert($users);
    }
}
