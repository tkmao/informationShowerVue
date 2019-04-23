<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

// 本来は、
// $factory->define(App\User::class, function (Faker $faker) {
// のような形だが、 use ($factory) を入れている。
// 理由は、faker ではなく指定した値を使いたい場合に使用するため
// 例えば、クロージャの中でcreate(['thread_id' => $thread->id])とすることで、生成されるReplyインスタンスのthread_id属性をオーバーライド出来る。
// https://qiita.com/Yorinton/items/b5a01ffedf3dc387c246
// クロージャは、変数を親のスコープから引き継ぐことができます。 引き継ぐ変数は、use で渡さなければなりません。
// 無名関数 use 構文でパラメータの受け渡し
// http://php.net/manual/ja/functions.anonymous.php

$factory->define(App\Repositories\Models\User::class, function (Faker $faker) use ($factory) {
    $maxWorktimeMonthList = array(10, 20, 30, 40, 50);

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password'  => bcrypt('pass'),
        'remember_token' => str_random(60),
        'api_token' => str_random(60),
        'usertype_id' => App\Repositories\Models\UserType::inRandomOrder()->value('id'),
        'workingtime_type' => random_int(1, 2),
        'worktime_day' => random_int(6, 8),
        'maxworktime_month' => $maxWorktimeMonthList[array_rand($maxWorktimeMonthList, 1)],
        'workingtime_min' => 152,
        'workingtime_max' => 200,
        'paid_holiday' => random_int(3, 13),
        'is_admin'  => false,
        'is_deleted' => false,
    ];
});
