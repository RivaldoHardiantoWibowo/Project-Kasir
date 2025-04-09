<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = [
        'name',
        'price',
        'image',
        'stock'
    ];

    public function cart(){
        return $this->hasMany(Products::class, 'product_id');
    }
}
