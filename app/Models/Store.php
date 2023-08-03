<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [

        'sales__bill_id',
        'medicine_id',
        'product_id',
        'name',
        'quantity_sold',
        'unit_price',
        'total_price',
        'image_url',

    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    protected $casts = [
        'sales__bill_id' => 'integer',
        'medicine_id' => 'integer',
        'product_id' => 'integer',
        'quantity_sold' => 'integer',
        'unit_price' => 'double',
        'total_price' => 'double',
    ];
}
