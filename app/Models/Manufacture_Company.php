<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacture_Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'company_name',
    ];

    public function medicines(){
        return $this->hasMany(Medicine::class , 'company_id');

    }


}
