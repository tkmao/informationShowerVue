<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1ここに指定すれば、「php artisan db:seed」でデータ投入できる。（クラス指定なしに）
        $this->call(UserTypesTableSeeder::class);
        $this->call(AdminsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(HolidaysTableSeeder::class);
        $this->call(ProjectStatusesTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(CompaniesTableSeeder::class);
        $this->call(ProjectsTableSeeder::class);
        $this->call(WorkSchedulesTableSeeder::class);
        $this->call(ProjectWorksTableSeeder::class);
        $this->call(WeeklyReportsTableSeeder::class);
        $this->call(WorkScheduleMonthsTableSeeder::class);
    }
}
