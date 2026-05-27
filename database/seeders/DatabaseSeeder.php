<?php

namespace Database\Seeders;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
    
        Admin::create([
            'name' => 'admin',
            'email' => 'admin@vgltuasianstudent.info',
            'password' => Hash::make('vgltu@voronezh'),
        ]);
    }
}
