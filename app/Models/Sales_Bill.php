<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales_Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_bill_id',
        'pharmacist_id',
        'today\'s date',
        'material_name',
        'quantity_sold',
        'unit_price',
        'total_price',
        'sale_confirmation',
        'image_url',

    ];

    public function salesBillsMedicines(){
        return $this->hasMany(Sales_Bill_Medicine::class , 'sales_bill_id');

    }

    public function salesBillsProducts(){
        return $this->hasMany(Sales_Bill_Product::class , 'sales_bill_id');

    }
}
