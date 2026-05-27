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
        'visa_reminder_90_sent_at',
        'visa_reminder_75_sent_at',
        'visa_reminder_60_sent_at',
        'visa_overdue_10_sent_at',
    ];

    protected $casts = [
        'visa_start_date' => 'date',
        'visa_expiry_date' => 'date',
        'visa_reminder_90_sent_at' => 'datetime',
        'visa_reminder_75_sent_at' => 'datetime',
        'visa_reminder_60_sent_at' => 'datetime',
        'visa_overdue_10_sent_at' => 'datetime',
    ];

    /**
     * Relationship: A student data entry belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
