<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentsData extends Model
{
    use HasFactory;

    protected $table = 'students_data'; // Define table name explicitly

    protected $fillable = [
        'user_id',
        'passport_number',
        'passport_photo',
        'visa_start_date',
        'visa_expiry_date',
        'visa_photo',
        'green_card_photo',
    ];

    /**
     * Relationship: A student data entry belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
