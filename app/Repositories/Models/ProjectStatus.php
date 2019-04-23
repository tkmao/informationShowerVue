<?php

namespace App\Repositories\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ProjectStatus extends Model
{
    use Sortable;

    protected $table = 'project_statuses';

    protected $fillable = [
        'name',
        'is_deleted'
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
    ];
}
