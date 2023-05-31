<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales_Bill_Product extends Model
{
    use HasFactory;

    protected $fillable = [

        'sales_bill_product_id',
        'product_id ',
        'sales_bill_id ',
        ];
}
