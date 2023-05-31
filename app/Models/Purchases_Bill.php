<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchases_Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchases_bill_id',
        'pharmacist_id',
        'today\'s date',
        'material_name',
        'all_amount',
        'unit_price',
        'total_price',
        'storehouse_name',
        'Statement',
        'image_url',
    ];

    public function purchasesBillsMedicines(){
        return $this->hasMany(Purchases_Bill_Medicine::class );

    }

    public function purchasesBillsProducts(){
        return $this->hasMany(Purchases_Bill_Product::class );

    }
}
