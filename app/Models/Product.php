<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [

        'product_id',
        'category_id',
        'barcode',
        'name',
        'type',
        'combination',
        'caliber',
        'amount',
        'common_price',
        'total_price',
        'image_url',
        'production_date',
        'expiration_date',

    ];

    public function purshasesBillsProducts(){
        return $this->hasMany(Purchases_Bill_Product::class , 'product_id');

    }

    public function salesBillsProducts(){
        return $this->hasMany(Sales_Bill_Product::class );

    }

    public function pharmacistProducts(){
        return $this->hasMany(Pharmacist_Product::class );

    }

}
