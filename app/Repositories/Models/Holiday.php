<?php

namespace App\Repositories\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Holiday extends Model
{
    use Sortable;

    protected $table = 'holidays';

    protected $fillable = [
        'date',
        'name'
    ];

    public $sortable = [
        'date'
    ];
}
