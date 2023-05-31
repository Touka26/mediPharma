<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacist_Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'pharmacist_medicine_id',
        'pharmacist_id',
        'medicine_id',

    ];
}
