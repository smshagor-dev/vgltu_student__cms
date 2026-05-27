<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category_type_id', 'photo'];
    

    public function categoryType()
    {
        return $this->belongsTo(CategoryType::class, 'category_type_id');
    }

    // Accessor to get the full storage path for the photo
    public function getPhotoUrlAttribute()
    {
        return $this->photo ? asset('storage/category_photos/' . $this->photo) : asset('images/default.jpg');
    }

    /**
     * Define the relationship between Category and SubCategory
     */
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);  
    }

    public function mediaUploads()
    {
        return $this->hasManyThrough(
            MediaUpload::class,
            CategoryType::class,
            'id', // Foreign key on the category_types table
            'category_type_id', // Foreign key on the media_uploads table
            'category_type_id', // Local key on the categories table
            'id' // Local key on the category_types table
        );
    }
    
}
