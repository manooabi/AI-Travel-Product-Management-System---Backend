<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
     protected $fillable = [
        'product_name',
        'destination',
        'category',
        'description',
        'highlights',
        'inclusions',
        'tags',
        'price',
        'inventory_count',
        'valid_from',
        'valid_until',
        'status',
    ];
        protected function casts(): array
    {
        return [
            'highlights' => 'array',
            'inclusions' => 'array',
            'tags' => 'array',
            'price' => 'decimal:2',
            'inventory_count' => 'integer',
            'valid_from' => 'datetime',
            'valid_until' => 'datetime',
        ];
    }
}
