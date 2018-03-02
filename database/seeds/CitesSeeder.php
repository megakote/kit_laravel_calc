<?php

use Illuminate\Database\Seeder;

class CitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\City::class, 1000)->create();
    }
}
