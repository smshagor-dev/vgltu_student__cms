<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'field_names',
        'field_definitions',
        'is_active',
    ];

    protected $casts = [
        'field_names' => 'array',
        'field_definitions' => 'array',
        'is_active' => 'boolean',
    ];

    public function submissions()
    {
        return $this->hasMany(CampaignSubmission::class);
    }

    public function normalizedFieldDefinitions(): array
    {
        $definitions = collect($this->field_definitions ?? [])
            ->map(function ($definition) {
                return [
                    'label' => trim((string) ($definition['label'] ?? '')),
                    'type' => ($definition['type'] ?? 'text') === 'checkbox' ? 'checkbox' : 'text',
                ];
            })
            ->filter(fn ($definition) => $definition['label'] !== '')
            ->values()
            ->all();

        if ($definitions !== []) {
            return $definitions;
        }

        return collect($this->field_names ?? [])
            ->map(fn ($fieldName) => [
                'label' => trim((string) $fieldName),
                'type' => 'text',
            ])
            ->filter(fn ($definition) => $definition['label'] !== '')
            ->values()
            ->all();
    }
}
