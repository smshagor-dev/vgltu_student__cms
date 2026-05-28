<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NotificationEmailCampaign extends Model
{
    protected $fillable = [
        'created_by_admin_id',
        'recipient_type',
        'title',
        'description',
        'body_html',
        'url',
        'total_recipients',
        'sent_count',
        'failed_count',
        'status',
        'started_at',
        'last_sent_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'last_sent_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by_admin_id');
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(NotificationEmailRecipient::class, 'campaign_id');
    }
}
