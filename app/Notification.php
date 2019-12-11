<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id', 'phone_number', 'reply', 'date', 'time', 'restaurant_name', 'restaurant_url',
    ];
}