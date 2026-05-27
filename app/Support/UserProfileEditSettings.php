<?php

namespace App\Support;

class UserProfileEditSettings
{
    public static function fields(): array
    {
        return [
            'email' => [
                'label' => 'Email Address',
                'type' => 'email',
                'description' => 'Login email and main communication address.',
            ],
            'room_number' => [
                'label' => 'Room Number',
                'type' => 'text',
                'description' => 'Student room or hostel room number.',
            ],
            'mobile_number' => [
                'label' => 'Mobile Number',
                'type' => 'text',
                'description' => 'Active phone number for quick contact.',
            ],
            'course_type' => [
                'label' => 'Course Type',
                'type' => 'select',
                'description' => 'Academic program category.',
                'options' => [
                    'Language' => 'Language',
                    'BSC' => 'BSC',
                    'MSC' => 'MSC',
                    'PHD' => 'PHD',
                ],
            ],
            'department' => [
                'label' => 'Department',
                'type' => 'select',
                'description' => 'Current department or study track.',
                'options' => [
                    'Prepetory Language Course' => 'Prepetory Language Course',
                    'Automobile' => 'Automobile',
                    'Forestry' => 'Forestry',
                    'Mechanical' => 'Mechanical',
                    'Computer Science and Technology' => 'Information Technology / IT',
                    'Economics' => 'Economics',
                    'Landscape Architecture' => 'Landscape Architecture',
                    'Tourism' => 'Tourism',
                    'automation of production processes' => 'automation of production processes',
                    'Life Safety and Legal Relations' => 'Life Safety and Legal Relations',
                    'Botany and Plant Physiology' => 'Botany and Plant Physiology',
                    'Hardware and Software' => 'Hardware and Software',
                ],
            ],
            'course_year' => [
                'label' => 'Course Year',
                'type' => 'select',
                'description' => 'Current academic year.',
                'placeholder' => '-- Select Course Year --',
                'options' => [
                    '1st Year' => '1st Year',
                    '2nd Year' => '2nd Year',
                    '3rd Year' => '3rd Year',
                    'Final Year' => 'Final Year',
                ],
            ],
            'course_language' => [
                'label' => 'Course Language',
                'type' => 'select',
                'description' => 'Preferred study language.',
                'placeholder' => '-- Select Course Language --',
                'options' => [
                    'English' => 'English',
                    'Russian' => 'Russian',
                ],
            ],
        ];
    }

    public static function defaultEditableFields(): array
    {
        return array_keys(static::fields());
    }

    public static function normalizeEditableFields($fields): array
    {
        if (! is_array($fields)) {
            return static::defaultEditableFields();
        }

        $allowedKeys = array_keys(static::fields());

        return array_values(array_intersect($allowedKeys, array_unique($fields)));
    }
}
