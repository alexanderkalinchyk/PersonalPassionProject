<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offset extends Model
{
    protected $fillable = [
        'user_id', 'offset',
    ];
}