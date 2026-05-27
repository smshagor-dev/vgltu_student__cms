<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryType extends Model
{
    use HasFactory;

    protected $fillable = ['type'];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
