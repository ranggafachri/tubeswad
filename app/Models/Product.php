<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'barcode',
        'description',
        'price',
        'stock',
        'image',
        'is_active',
        'category_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
