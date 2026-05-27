<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomField extends Model
{
    use HasFactory;

    protected $table = 'user_custom_fields';

    protected $fillable = ['field_label', 'field_type', 'target_audience'];

    // protected $casts = [
    //     'options' => 'array', // Automatically convert JSON to array
    // ];

    public function userFieldData()
    {
        return $this->hasMany(UserFieldData::class, 'field_id');
    }
    
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_custom_fields', 'custom_field_id', 'user_id');
    }
    
    public function options()
    {
        return $this->hasMany(UserCustomFieldOption::class, 'user_custom_field_id');
    }
}
