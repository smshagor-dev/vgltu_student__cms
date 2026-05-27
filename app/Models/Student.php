<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'photo_path', 'degree', 'department', 'pass_year',
    ];
    
    protected $casts = [
        'degree' => 'array',
        'department' => 'array',
        'pass_year' => 'array',
    ];
}
