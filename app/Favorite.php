<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [
        'user_id', 'business_id', 'name', 'image_url', 'price', 'distance',
    ];
}