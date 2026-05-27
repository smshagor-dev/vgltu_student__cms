<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCustomField extends Model
{
    use HasFactory;

    protected $fillable = ['field_label', 'field_type', 'target_audience'];

    public function options()
    {
        return $this->hasMany(UserCustomFieldOption::class, 'user_custom_field_id');
    }

    public function userFieldData()
    {
        return $this->hasMany(UserFieldData::class, 'field_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
