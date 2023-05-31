<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands = [
            ['name'=>'Nvidia', 'status'=>true],
            ['name'=>'ASUS', 'status'=>true],
            ['name'=>'Cooler Master', 'status'=>true],
            ['name'=>'Logitech', 'status'=>true],
            ['name'=>'Lian Li', 'status'=>true],
            ['name'=>'Samsung', 'status'=>true],

        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('brand')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('brand')->insert($brands);
    }
}
