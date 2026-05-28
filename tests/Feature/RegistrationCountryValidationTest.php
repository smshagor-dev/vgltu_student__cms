<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegistrationCountryValidationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_registration_accepts_country_when_phone_country_code_is_submitted_separately(): void
    {
        Storage::fake('public');
        Mail::fake();

        $email = 'regtest_'.time().'@example.com';

        $response = $this->post(route('register'), [
            'room_number' => '101A',
            'full_name' => 'Registration Test User',
            'email' => $email,
            'country' => 'Bangladesh',
            'phone_country_code' => 'BD',
            'mobile_number' => '+8801712345678',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'address' => 'Dhaka',
            'religion' => 'Muslim',
            'gender' => 'Male',
            'date_of_birth' => '2000-01-01',
            'course_type' => 'BSC',
            'department' => 'Automobile',
            'course_year' => '1st Year',
            'course_language' => 'English',
            'photo' => UploadedFile::fake()->image('profile.jpg'),
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasNoErrors(['country']);

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'country' => 'Bangladesh',
            'full_name' => 'Registration Test User',
        ]);

        $user = User::where('email', $email)->first();

        $this->assertNotNull($user);
        $this->assertStringStartsWith('photos/', (string) $user->photo);
    }
}
