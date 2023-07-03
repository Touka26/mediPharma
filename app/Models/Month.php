<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    use HasFactory;


    protected $fillable = [
        'employee_id',
        'month',
        'salary',
    ];

    protected $casts = [
        'employee_id' => 'integer',
        'salary' => 'double',
    ];
}
