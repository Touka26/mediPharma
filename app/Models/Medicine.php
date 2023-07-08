<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        //'medicine_id',
        'manufacture_id',
        'barcode',
        'trade_name',
        'combination',
        'caliber',
        'type',
        'pharmaceutical_form',
        'net_price',
        'common_price',
//        'total_price',
        'amount',
        'statement',
        'prescription_url',
        'id_number',
        'image_url',
        'production_date',
        'expiration_date',
    ];

    public function pharmacistsMedicines()
    {
        return $this->hasMany(Pharmacist_Medicine::class);
    }

    public function purchasesBillMedicines()
    {
        return $this->hasMany(Purchases_Bill_Medicine::class);
    }

    public function salesBillMedicines()
    {
        return $this->hasMany(Sales_Bill_Medicine::class);
    }

    protected $casts = [
        'manufacture_id' => 'integer',
        'amount' => 'integer',
        'id_number' => 'integer',
        'net_price' => 'double',
        'common_price' => 'double',
//        'total_price' => 'double',
        'statement' => 'boolean'
    ];
}
