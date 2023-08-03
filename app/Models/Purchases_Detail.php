<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchases_Detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchases__bill_id',
        'product_id',
        'medicine_id',
        'amount',
        'unit_price',
        'total_price',
    ];


    protected $casts = [
        'purchases__bill_id' => 'integer',
        'product_id' => 'integer',
        'medicine_id' => 'integer',
        'amount' => 'integer',
        'unit_price' => 'double',
        'total_price' => 'double',
    ];
    public function purchasesBill()
    {
        return $this->belongsTo(Purchases_Bill::class, 'purchases_bill_id');
    }
    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
