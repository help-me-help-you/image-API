<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'name', 'extension', 'is_main'];

    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault();
    }

    public function isMain()
    {
        return $this->is_main = true;
    }

    public function isNotMain()
    {
        return $this->is_main = false;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['extension'] = \File::extension($value);
    }
}
