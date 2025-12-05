<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    // If you want, you can leave $fillable empty and assign manually in controller.
    protected $fillable = [
        'name',
        'phone',
        'email',
        'comment',
        'schedule_date',
        'schedule_time',
        'type',
    ];
}
