<?php

namespace App\Support;

class UserProfileEditSettings
{
    public static function fields(): array
    {
        return [
            'full_name' => [
                'label' => 'Full Name',
                'type' => 'text',
                'description' => 'Official student full name used across the portal.',
            ],
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
            'country' => [
                'label' => 'Country',
                'type' => 'select',
                'description' => 'Home country listed on your student profile.',
                'placeholder' => '-- Select Country --',
                'options' => [
                    'Bangladesh' => 'Bangladesh',
                    'India' => 'India',
                    'Nepal' => 'Nepal',
                ],
            ],
            'address' => [
                'label' => 'Address',
                'type' => 'textarea',
                'description' => 'City or address text exactly as required for your record.',
                'rows' => 4,
                'full_width' => true,
            ],
            'religion' => [
                'label' => 'Religion',
                'type' => 'select',
                'description' => 'Religion shown on your profile.',
                'placeholder' => '-- Select Religion --',
                'options' => [
                    'Muslim' => 'Muslim',
                    'Hindu' => 'Hindu',
                    'Boddho' => 'Boddho',
                    'Cristan' => 'Cristan',
                ],
            ],
            'gender' => [
                'label' => 'Gender',
                'type' => 'select',
                'description' => 'Gender information for your student record.',
                'placeholder' => '-- Select Gender --',
                'options' => [
                    'Male' => 'Male',
                    'Female' => 'Female',
                ],
            ],
            'date_of_birth' => [
                'label' => 'Date of Birth',
                'type' => 'date',
                'description' => 'Birth date used in your student profile.',
            ],
            'course_type' => [
                'label' => 'Course Type',
                'type' => 'select',
                'description' => 'Academic program category.',
                'placeholder' => '-- Select Course Type --',
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
                'full_width' => true,
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
            'photo' => [
                'label' => 'Profile Photo',
                'type' => 'file',
                'description' => 'Student profile image shown in the portal.',
            ],
            'password' => [
                'label' => 'Password',
                'type' => 'password',
                'description' => 'Allow students to change their account password from profile edit.',
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
