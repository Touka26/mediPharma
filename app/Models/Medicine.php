<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id',
        'company_id',
        'barcode',
        'trade_name',
        'combination',
        'caliber',
        'type',
        'pharmaceutical_form',
        'common_price',
        'amount',
        'statement',
        'prescription_url',
        'id_number',
        'image_url',
        'production_date',
        'expiration_date',
    ];

    public function pharmacistsMedicines(){
        return $this->hasMany(Pharmacist_Medicine::class );
    }

    public function purchasesBillMedicines(){
        return $this->hasMany(Purchases_Bill_Medicine::class );
    }

    public function salesBillMedicines(){
        return $this->hasMany(Sales_Bill_Medicine::class );
    }
}
