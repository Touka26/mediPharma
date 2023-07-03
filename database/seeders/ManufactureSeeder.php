<?php

namespace Database\Seeders;

use App\Models\Manufacture;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ManufactureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    ///toDo
    public function run()
    {

//        Manufacture::query()->create(
//            [
//                'company_name' => 'pharma'
//            ]
//        );
        DB::table('manufactures')->insert(array(
            array(
                'company_name' => "Pharma"
            ),
        array(
                'company_name' => "hhhh"
            ),

        ));


    }
}
