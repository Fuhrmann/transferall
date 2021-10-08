<?php

namespace Database\Seeders;

use App\Models\CompanyUser;
use App\Models\IndividualUser;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() : void
    {
        IndividualUser::factory(10)->create();
        CompanyUser::factory(10)->create();
    }
}
