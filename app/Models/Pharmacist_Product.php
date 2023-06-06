<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacist_Product extends Model
{
    use HasFactory;

    protected $fillable = [

      //  'pharmacist_product_id',
        'pharmacist_id',
        'product_id',
    ];
     protected $casts = [

         'pharmacist_id' => 'integer',
         'product_id'=> 'integer',
         ];

}
