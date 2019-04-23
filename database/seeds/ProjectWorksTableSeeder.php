<?php

use App\Repositories\Models\ProjectWork;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ProjectWorksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('project_works')->truncate();

        $projectWorks = new ProjectWork();
        $projectWorks->id = 1;
        $projectWorks->work_schedule_id = 32;
        $projectWorks->project_id = 1;
        $projectWorks->worktime = 7;
        $projectWorks->save();

        $projectWorks = new ProjectWork();
        $projectWorks->id = 2;
        $projectWorks->work_schedule_id = 32;
        $projectWorks->project_id = 5;
        $projectWorks->worktime = 1;
        $projectWorks->save();

        $projectWorks = new ProjectWork();
        $projectWorks->id = 3;
        $projectWorks->work_schedule_id = 32;
        $projectWorks->project_id = 2;
        $projectWorks->worktime = 0;
        $projectWorks->save();

        $projectWorks = new ProjectWork();
        $projectWorks->id = 4;
        $projectWorks->work_schedule_id = 33;
        $projectWorks->project_id = 1;
        $projectWorks->worktime = 0;
        $projectWorks->save();

        $projectWorks = new ProjectWork();
        $projectWorks->id = 5;
        $projectWorks->work_schedule_id = 33;
        $projectWorks->project_id = 5;
        $projectWorks->worktime = 0;
        $projectWorks->save();

        $projectWorks = new ProjectWork();
        $projectWorks->id = 6;
        $projectWorks->work_schedule_id = 33;
        $projectWorks->project_id = 2;
        $projectWorks->worktime = 7;
        $projectWorks->save();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Model::reguard();
    }
}
