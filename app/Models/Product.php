<?php

namespace App\Models;

use App\Models\Attachment;
use App\Models\Categories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    public function image()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
