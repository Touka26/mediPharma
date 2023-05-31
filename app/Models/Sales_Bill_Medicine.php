<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales_Bill_Medicine extends Model
{
    use HasFactory;


    protected $fillable = [

        'sales_bill_medicine_id',
        'medicine_id ',
        'sales_bill_id ',
    ];
}

