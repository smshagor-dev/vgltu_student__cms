<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserCustomFieldOption extends Model
{
    
    use HasFactory;
    
    protected $fillable = ['user_custom_field_id', 'option_value', 'field_id', 'value_id'];

    public function customField()
    {
        return $this->belongsTo(UserCustomField::class, 'user_custom_field_id');
    }
    
    protected $table = 'user_custom_field_options';

    public function field()
    {
        return $this->belongsTo(CustomField::class, 'field_id');
    }
    
    public function userFieldData()
    {
        return $this->hasMany(UserFieldData::class, 'value_id');
        return $this->belongsTo(UserCustomField::class, 'user_custom_field_id');
    }
}
