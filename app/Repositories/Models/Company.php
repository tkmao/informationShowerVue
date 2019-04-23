<?php

namespace App\Repositories\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Company extends Model
{
    use Sortable;

    protected $table = 'companies';

    protected $fillable = [
        'name',
        'zipcode',
        'address',
        'phone',
        'fax',
        'is_deleted'
    ];

    public $sortable = [
        'name'
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
    ];
}
