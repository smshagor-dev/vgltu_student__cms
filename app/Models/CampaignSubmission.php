<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'user_id',
        'submission',
    ];

    protected $casts = [
        'submission' => 'array',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
