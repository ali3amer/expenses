<?php

use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = ['الشماليه' , 'الخرطوم'];

        foreach ($states as $state) {
            \App\State::create(['name' => $state]);
        }
    }
}
