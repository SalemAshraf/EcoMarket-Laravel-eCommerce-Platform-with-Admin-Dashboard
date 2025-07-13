<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function Category()
    {
        return $this->belongsTo(Category::class, 'cat_id');

    }

    public function Brand()
    {
       return $this->belongsTo(Brand::class, 'brand_id');
    }
}
