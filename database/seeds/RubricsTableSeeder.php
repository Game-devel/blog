<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RubricsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rubrics')->insert([
            'name' => 'sport',
        ]);
    }
}
