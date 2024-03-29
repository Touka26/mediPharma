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
        'piece_price',
        'common_price',
        'image_url',
        'production_date',
        'expiration_date',

    ];

    use HasFactory;

    public function purchasesDetails()
    {
        return $this->hasMany(Purchases_Detail::class);

    }

    public function pharmacistProducts()
    {
        return $this->hasMany(Pharmacist_Product::class);

    }

    public function category()
    {
        return $this->belongsTo(Category::class);

    }

    public function store(){
        return $this->hasMany(Store::class);
    }

    protected $casts = [
        'category_id' => 'integer',
        'amount' => 'integer',
        'common_price' => 'double',
        'piece_price' => 'double',
    ];

}
