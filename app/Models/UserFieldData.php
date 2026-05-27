<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFieldData extends Model
{
    use HasFactory;

    // Allow 'description' to be mass assignable
    protected $fillable = ['user_id', 'field_id', 'value_id', 'value', 'description', 'status', 'created_at', 'updated_at', 'updated_by'];

    // Cast 'description' as JSON to easily store and retrieve data
    // protected $casts = [
    //     'description' => 'array',
    // ];

    /**
     * Relationship: Each UserFieldData belongs to a custom field.
     */
    public function customField()
    {
        return $this->belongsTo(UserCustomField::class, 'field_id');
        return $this->belongsTo(CustomField::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class); // 'user_id' is the foreign key
    }
    
    public function options()
    {
        return $this->belongsToMany(UserCustomFieldOption::class, 'user_field_data_options', 'user_field_data_id', 'user_custom_field_option_id', 'value_id');
    }

    


}