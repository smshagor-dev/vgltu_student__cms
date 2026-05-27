<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = ['file_path', 'category_id', 'path'];

    // Define the inverse of the relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
