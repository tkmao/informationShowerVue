<?php

namespace App\Repositories\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Category extends Model
{
    use Sortable;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'is_deleted'
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
    ];
}
