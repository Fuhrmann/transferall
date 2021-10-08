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
        IndividualUser::factory(3)->create();
        CompanyUser::factory(3)->create();
    }
}
