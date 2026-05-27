<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroFlag extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_section_id',
        'label',
        'image_path',
        'position_top',
        'position_left',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'position_top' => 'integer',
        'position_left' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function heroSection()
    {
        return $this->belongsTo(HeroSection::class);
    }
}
