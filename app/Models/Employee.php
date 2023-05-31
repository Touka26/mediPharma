<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'pharmacist_id',
        'first_name',
        'last_name',
        'birth_date',
        'email',
        'CV_file',
        'salary',
        'lock',
        'confirmation_lock',
        'work_start_date',
        'image_url',


    ];
}
