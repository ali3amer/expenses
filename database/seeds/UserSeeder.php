<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\User::create([
            'name' => 'admin',
//            'email' => 'admin@test.com',
            'state_id' => 2,
            'power' => 'developer',
            'password' => Hash::make('123456')
        ]);
    }
}
