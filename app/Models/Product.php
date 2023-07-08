<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [

        //'product_id',
        'category_id',
        'barcode',
        'name',
        'type',
        'combination',
        'caliber',
        'amount',
        'piece\'s_price',
        'common_price',
        'image_url',
        'production_date',
        'expiration_date',

    ];

    use HasFactory;

    public function purshasesBillsProducts()
    {
        return $this->hasMany(Purchases_Bill_Product::class, 'product_id');

    }

    public function salesBillsProducts()
    {
        return $this->hasMany(Sales_Bill_Product::class);

    }

    public function pharmacistProducts()
    {
        return $this->hasMany(Pharmacist_Product::class);

    }

    protected $casts = [
        'category_id' => 'integer',
        'amount' => 'integer',
        'common_price' => 'double',
        'piece\'s_price' => 'double',
    ];

}
