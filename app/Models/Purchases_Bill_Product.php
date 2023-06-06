<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchases_Bill_Product extends Model
{
    use HasFactory;

    protected $fillable = [
        //'purchases_bill_product_id',
        'product_id ',
        'purchases_bill_id ',
    ];

    protected $casts = [
        'product_id '  =>'integer',
        'purchases_bill_id ' =>'integer',

    ];

}
