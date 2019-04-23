<?php

namespace App\Repositories\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class UserType extends Model
{
    use Sortable;

    protected $table = 'user_types';

    protected $fillable = [
        'name'
    ];

    public $sortable = [
        'name'
    ];
}
