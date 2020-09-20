<?php

namespace App;

use App\ProductImage;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const AVAILABLE_PRODUCT = '1';
    const UNAVAILABLE_PRODUCT = '0';

    protected $fillable = ['name', 'slug', 'sku', 'price', 'description', 'quantity', 'status'];

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function isAvailable()
    {
        return $this->status == Product::AVAILABLE_PRODUCT;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value, '-');
    }
}
