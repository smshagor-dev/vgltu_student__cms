<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MediaUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category_type_id',
        'category',
        'sub_category',
        'file_path',
        'file_type',
        'description',
    ];

    public function categoryType()
    {
        return $this->belongsTo(CategoryType::class, 'category_type_id', 'id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

}
