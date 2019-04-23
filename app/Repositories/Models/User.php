<?php

namespace App\Repositories\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;

class User extends Authenticatable
{
    use Notifiable;

    use Sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token',
    ];

    public $sortable = [
        'name',
        'email',
        'hiredate'
    ];

    public function workSchedule()
    {
        return $this->hasMany('App\Repositories\Models\WorkSchedule', 'user_id');
    }

    public function weeklyReport()
    {
        return $this->hasMany('App\Repositories\Models\WeeklyReport', 'user_id');
    }

    public function userType()
    {
        return $this->belongsTo('App\Repositories\Models\UserType', 'usertype_id', 'id');
    }
}
