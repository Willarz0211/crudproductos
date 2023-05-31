<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name'=>'Trjetas de video', 'status'=>true],
            ['name'=>'Monitores', 'status'=>true],
            ['name'=>'Periferico', 'status'=>true],
            ['name'=>'Gabinete', 'status'=>true],

        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('category')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('category')->insert($brands);
    }
}
