<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'productName',
        'description',
        'category',
        'price',
        'image',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    // Accessor to map productName to name
    public function getNameAttribute()
    {
        return $this->productName;
    }

    // Mutator to map name to productName
    public function setNameAttribute($value)
    {
        $this->attributes['productName'] = $value;
    }
}