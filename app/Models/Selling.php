<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Selling extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'member_id',
        'total_price',
        'user_id',
        'product_id',
        'qty',
    ];
}
