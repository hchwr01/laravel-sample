<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todo extends Model
{
    use SoftDeletes;

    const STATUS_COMPLETED = 1;
    const STATUS_NOT_COMPLETED = 0;

    protected $fillable = [
        'title',
        'discription',
        'completed'
    ];
}
