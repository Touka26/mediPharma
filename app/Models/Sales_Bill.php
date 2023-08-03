<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales_Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        //'sales_bill_id',
        'pharmacist_id',
        'today_date',
    ];

    public function store(){
        return $this->hasMany(Store::class);
    }

    public function sales_details()
    {
        return $this->hasMany(Sales_Detail::class);
    }

    protected $casts = [
        'pharmacist_id' => 'integer',
    ];
}
