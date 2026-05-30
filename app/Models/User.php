<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;
use App\Models\StudentsData;
use App\Models\CampaignSubmission;
use App\Models\NotificationEmailRecipient;
use App\Models\UserNotification;
use App\Models\EmergencyContact;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasPushSubscriptions;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    
    protected $fillable = [
        'room_number', 'full_name', 'email', 'mobile_number',
        'country', 'address', 'religion', 'gender', 'date_of_birth', 'course_type',
        'department', 'course_year', 'approved', 'course_language', 'photo', 'password', 'registration_password_plain',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'date_of_birth' => 'date',
            'password' => 'hashed',
            'browser_notifications_enabled' => 'boolean',
            'registration_password_plain' => 'encrypted',
        ];
        
    }

    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function fieldData()
    {
        return $this->hasMany(UserFieldData::class);
    }

     // Ensure the table name is correct

     public function studentsData()
     {
         return $this->hasOne(StudentsData::class, 'user_id', 'id');
     }
    
    
    //Custom fields

    public function customFields(): BelongsToMany
    {
        return $this->belongsToMany(CustomField::class, 'user_custom_fields', 'user_id', 'custom_field_id')
                    ->withPivot('option', 'description');
    }
    
    // User.php
    public function userFieldData()
    {
        return $this->hasMany(UserFieldData::class, 'user_id');
    }

    public function campaignSubmissions()
    {
        return $this->hasMany(CampaignSubmission::class);
    }

    public function userNotifications()
    {
        return $this->hasMany(UserNotification::class)->latest();
    }

    public function emergencyContacts()
    {
        return $this->hasMany(EmergencyContact::class)->latest();
    }

    public function notificationEmailRecipients()
    {
        return $this->hasMany(NotificationEmailRecipient::class)->latest();
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}
