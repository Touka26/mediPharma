<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [

        /*'notification_id',*/
        'admin_id',
        'pharmacist_id',
        'title',
        'body',
        'image_url'

    ];

    protected $casts = [
        'admin_id'=>'integer',
        'pharmacist_id'=>'integer',
    ];
}
