<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'first_name',
        'last_name',
        'email',
        'password',
    ];


    public function ordersProcessing(){
        return $this->hasMany(Orders_Processing::class);
    }

    public function notifications(){
        return $this->hasMany(Notification::class);
    }
}
