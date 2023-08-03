<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchases_Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        //  'purchases_bill_id',
        'pharmacist_id',
        'today_date',
        'storehouse_name',
        'statement',
        'image_url',
    ];

    public function details()
    {
        return $this->hasMany(Purchases_Detail::class, 'purchases__bill_id');
    }

    protected $casts = [
        'pharmacist_id' => 'integer',

    ];
}
