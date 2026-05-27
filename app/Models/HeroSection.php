<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'badge_text',
        'title',
        'subtitle',
        'background_image_path',
        'cta_text',
        'cta_link',
        'overlay_start_color',
        'overlay_end_color',
        'overlay_opacity',
        'is_active',
    ];

    protected $casts = [
        'overlay_opacity' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function images()
    {
        return $this->hasMany(HeroImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function flags()
    {
        return $this->hasMany(HeroFlag::class)->orderBy('sort_order');
    }
}
