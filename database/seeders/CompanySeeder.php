<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create(['company_name' => 'アサヒ']);
        Company::create(['company_name' => '伊藤園']);
        Company::create(['company_name' => 'コカ・コーラ']);
        Company::create(['company_name' => 'キリン']);
        Company::create(['company_name' => 'ダイドー']);
    }
}
