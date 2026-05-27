<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'photo_path'];

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
}
