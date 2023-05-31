<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchases_Bill_Medicine extends Model
{
    use HasFactory;

    protected $fillable = [

        'purchases_bill_medicine_id',
        'medicine_id ',
        'purchases_bill_id ',
    ];

}
