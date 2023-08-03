<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Pharmacist extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
       // 'pharmacist_id',
        'first_name',
        'middle_name',
        'last_name',
        'registration_number',
        'registration_date',
        'released_on_date',
        'city',
        'region',
        'name_of_pharmacy',
        'landline_phone_number',
        'mobile_number',
        'copy_of_the_syndicate_card_url',
        'email',
        'password',
        'password_confirmation',
        'image_url',
        'financial_fund'
    ];

    public function pharmacistsMedicines(){
        return $this->hasMany(Pharmacist_Medicine::class );
    }

    public function pharmacistsProducts(){
        return $this->hasMany(Pharmacist_Product::class );
    }

    public function orderProcessing(){
        return $this->belongsTo(Orders_Processing::class);
    }

    public function employees(){
        return $this->hasMany(Employee::class);
    }

    public function purchases_Bills(){
        return $this->hasMany(Purchases_Bill::class);
    }

    public function sales_Bills(){
        return $this->hasMany(Sales_Bill::class);
    }

    protected $casts = [
        'registration_number' => 'integer',
        'landline_phone_number' => 'integer',
        'mobile_number' => 'integer',
        'financial_fund' => 'double',
        /*'registration_date'=>'date',
        'released_on_date'=>'date',*/
    ];

    protected $hidden = [
        'password',
        'password_confirmation',
        'remember_token',
    ];

}
