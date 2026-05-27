<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function mediaUploads()
    {
        return $this->hasMany(MediaUpload::class);
    }

    public function categoryPhotos()
    {
        return $this->hasMany(CategoryPhoto::class);
    }

    public function photos()
    {
        return $this->hasMany(MediaUpload::class)->where('file_type', 'photo');
    }
    
    public function videos()
    {
        return $this->hasMany(MediaUpload::class)->where('file_type', 'video');
    }
    
}
