<?php

use App\Repositories\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
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

        DB::table('admins')->truncate();

        $admins = new Admin();
        $admins->id = 1;
        $admins->name = '加茂剛';
        $admins->email = 'kamo@e3sys.co.jp';
        $admins->password = bcrypt('pass');
        $admins->api_token = str_random(60);
        $admins->is_deleted = false;
        $admins->save();

        $admins = new Admin();
        $admins->id = 2;
        $admins->name = '加茂剛2';
        $admins->email = 'kamo2@e3sys.co.jp';
        $admins->password = bcrypt('pass');
        $admins->api_token = str_random(60);
        $admins->is_deleted = true;
        $admins->save();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Model::reguard();
    }
}
