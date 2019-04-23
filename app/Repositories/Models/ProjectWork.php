<?php

namespace App\Repositories\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ProjectWork extends Model
{
    use Sortable;

    protected $table = 'project_works';

    protected $fillable = [
        'project_id',
        'work_schedule_id',
        'worktime'
    ];

    public $sortable = [
        'project_id',
        'work_schedule_id',
        'worktime'
    ];

    public function workSchedule()
    {
        return $this->belongsTo('App\Repositories\Models\WorkSchedule', 'work_schedule_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo('App\Repositories\Models\Project', 'project_id', 'id');
    }
}
