<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders_Processing extends Model
{
    use HasFactory;

    protected $fillable = [

       // 'order_processing_id',
        'pharmacist_id',
        'admin_id',
        'order_confirmation',
    ];

    protected $casts = [
        'pharmacist_id' => 'integer',
        'admin_id' => 'integer',
    ];

}
