<?php

namespace ProductPackage\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Product Model
 * 
 * This model is provided for convenience and only works if you use the provided migrations.
 * 
 * If you have your own database structure, you should create your own model or use your existing model.
 */
class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];
}