<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forbidden extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id',
        'prescription_url',
        'id_number',
    ];
    protected $casts = [
        'medicine_id' => 'integer',
        'id_number' => 'integer'
    ];

}
