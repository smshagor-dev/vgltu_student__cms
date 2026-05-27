<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'field_label', 
        'field_type',
        'options', // assuming it's stored as a string or JSON
    ];

    public function userFieldData()
    {
        return $this->hasMany(UserFieldData::class, 'field_id');
    }
}

