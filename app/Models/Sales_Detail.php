<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales_Detail extends Model
{
    use HasFactory;

    protected $fillable = [

        'store_id',
        'sales__bill_id',
        'medicine_id',
        'product_id',
        'name',
        'quantity_sold',
        'unit_price',
        'total_price',
        'image_url',

    ];

    public function store()
    {
        return $this->belongsTo(Sales_Detail::class);
    }
    protected $casts = [
        'store_id' => 'integer',
        'sales__bill_id' => 'integer',
        'medicine_id' => 'integer',
        'product_id' => 'integer',
        'quantity_sold' => 'integer',
        'unit_price' => 'double',
        'total_price' => 'double',
    ];
}
