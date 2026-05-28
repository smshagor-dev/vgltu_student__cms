<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegistrationFullFormSubmissionTest extends TestCase
{
    use DatabaseTransactions;

    public function test_registration_accepts_full_bsc_form_submission(): void
    {
        Storage::fake('public');
        Mail::fake();

        $email = 'fullform_' . time() . '@example.com';

        $response = $this->post(route('register'), [
            'room_number' => 'B-404',
            'full_name' => 'Full Registration User',
            'email' => $email,
            'country' => 'Bangladesh',
            'phone_country_code' => 'BD',
            'mobile_number' => '+8801712345678',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'address' => 'Dhaka',
            'religion' => 'Muslim',
            'gender' => 'Male',
            'date_of_birth' => '2001-05-10',
            'course_type' => 'BSC',
            'department' => 'Life Safety and Legal Relations',
            'course_year' => '3rd Year',
            'course_language' => 'English',
            'photo' => UploadedFile::fake()->image('student.jpg'),
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'full_name' => 'Full Registration User',
            'country' => 'Bangladesh',
            'course_type' => 'BSC',
            'department' => 'Life Safety and Legal Relations',
            'course_year' => '3rd Year',
            'course_language' => 'English',
            'gender' => 'Male',
            'religion' => 'Muslim',
        ]);

        $user = User::where('email', $email)->first();

        $this->assertNotNull($user);
        $this->assertStringStartsWith('photos/', (string) $user->photo);
    }
}
