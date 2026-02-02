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

    /**
     * Scope a query to search products by name.
     */
    public function scopeSearch($query, $term)
    {
        if ($term) {
            return $query->where('productName', 'LIKE', "%{$term}%");
        }
        return $query;
    }

    /**
     * Scope a query to filter products by category.
     */
    public function scopeCategory($query, $category)
    {
        if ($category && $category !== 'All') {
            return $query->where('category', $category);
        }
        return $query;
    }
}