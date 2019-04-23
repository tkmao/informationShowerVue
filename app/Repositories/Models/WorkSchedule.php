<?php

namespace App\Repositories\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class WorkSchedule extends Model
{
    use Sortable;

    protected $table = 'work_schedules';

    protected $fillable = [
        'user_id',
        'workdate',
        'week_number',
        'detail',
        'starttime',
        'endtime',
        'breaktime',
        'breaktime_midnight',
        'is_paid_holiday'
    ];

    public $sortable = [
        'user_id',
        'workdate',
        'week_number'
    ];

    protected $casts = [
        'workdate' => 'string',
    ];

    public function projectWork()
    {
        return $this->hasMany('App\Repositories\Models\ProjectWork', 'work_schedule_id');
    }

    public function holiday()
    {
        return $this->hasone('App\Repositories\Models\Holiday', 'date', 'workdate');
    }
}
