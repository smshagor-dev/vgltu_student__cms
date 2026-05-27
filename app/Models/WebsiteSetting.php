<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'logo_path',
        'favicon_path',
        'contact_button_text',
        'contact_button_link',
        'default_language',
        'available_languages',
        'topbar_location',
        'class_routine_text',
        'class_routine_link',
        'university_profile_text',
        'university_profile_link',
        'about_university_menu_text',
        'about_university_title',
        'about_university_content',
        'about_university_header_path',
        'courses_menu_text',
        'courses_title',
        'courses_content',
        'courses_header_path',
        'smtp_enabled',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'smtp_from_address',
        'smtp_from_name',
        'search_placeholder',
    ];

    protected $casts = [
        'available_languages' => 'array',
        'smtp_enabled' => 'boolean',
        'smtp_password' => 'encrypted',
    ];
}
