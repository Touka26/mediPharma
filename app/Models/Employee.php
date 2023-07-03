<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'employee_id',
        'pharmacist_id',
        'first_name',
        'last_name',
        'birth_date',
        'email',
        'CV_file',
        'phone_num',
        'work_start_date',
        'image_url',


    ];

    protected $casts = [
        'pharmacist_id' => 'integer',
        'phone_num' => 'integer',

    ];

    public function monthly(){
        return $this->belongsTo(Month::class);
    }
}
