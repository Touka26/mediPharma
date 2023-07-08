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

        DB::table('manufactures')->insert(array(
            array(
                'company_name' => "Hama Pharma Pharmaceutical Industries"
            ),
            array(
                'company_name' => "Ultra Medica Pharmaceutical Industries"
            ),
            array(
                'company_name' => "Unipharma"
            ),
            array(
                'company_name' => "Biomed Pharma co.LTD"
            ),
            array(
                'company_name' => "Bahri Pharmaceutical"
            ),
            array(
                'company_name' => "Medico Labs"
            ),
            array(
                'company_name' => "Mediotic Labs"
            ),
            array(
                'company_name' => "IbnAlhaytham Pharma"
            ),
            array(
                'company_name' => "Alfares Pharmaceuticals"
            ),
            array(
                'company_name' => "Ibnhayyan Pharma"
            ),
            array(
                'company_name' => "Razi Pharmaceutical Industries"
            ),
            array(
                'company_name' => "Delta Pharma"
            ),
            array(
                'company_name' => "Barakat Pharma"
            ),
            array(
                'company_name' => "Human Pharma"
            ),
            array(
                'company_name' => "Vita Pharmaceutical Industry"
            ),
            array(
                'company_name' => "Asia Pharmaceutical Industries"
            ),
            array(
                'company_name' => "El-Saad Pharma"
            ),
            array(
                'company_name' => "Aphamea Pharma"
            ),
            array(
                'company_name' => "Balsam Pharma"
            ),
            array(
                'company_name' => "Avenzor Pharma"
            ),
            array(
                'company_name' => "Oubari Pharma"
            ),
            array(
                'company_name' => "Domina Pharmaceuticals"
            ),
            array(
                'company_name' => "Rama Pharma"
            ),
            array(
                'company_name' => "IDM Pharma"
            ),
            array(
                'company_name' => "Rasha Pharma"
            ),
            array(
                'company_name' => "Golden Med Pharma"
            ),
            array(
                'company_name' => "LEM Pharma"
            ),
            array(
                'company_name' => "Pharmasyr"
            ),
            array(
                'company_name' => "Alpha Pharmaceutical Industries"
            ),
            array(
                'company_name' => "Ugarit Pharmaceutical Company"
            ),
            array(
                'company_name' => "Hayat Pharma"
            ),
            array(
                'company_name' => "Salama Care"
            ),
            array(
                'company_name' => "Zein Pharma"
            ),
            array(
                'company_name' => "SeaPharma"
            ),
            array(
                'company_name' => "Diamond Pharma"
            ),
            array(
                'company_name' => "United Pharma"
            ),
            array(
                'company_name' => "Nawras Pharma"
            ),
            array(
                'company_name' => "City Pharma"
            ),
            array(
                'company_name' => "Tamiko"
            ),
            array(
                'company_name' => "ProLine Pharma"
            ),
            array(
                'company_name' => "Alteriaq Pharma"
            ),
            array(
                'company_name' => "MPI Pharma"
            ),
            array(
                'company_name' => "Avencenna"
            ),
            array(
                'company_name' => "Kanawati"
            ),

        ));


    }
}
