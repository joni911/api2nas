<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Membuat user admin awal. Fitur registrasi publik dimatikan, sehingga akun
 * pertama (dan seterusnya) dibuat oleh admin melalui halaman User Management
 * atau lewat seeder ini.
 */
class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = (string) env('ADMIN_EMAIL', 'admin@example.com');

        User::firstOrCreate(
            ['email' => $email],
            [
                'name' => (string) env('ADMIN_NAME', 'Administrator'),
                'password' => Hash::make((string) env('ADMIN_PASSWORD', 'password')),
                'email_verified_at' => now(),
            ]
        );
    }
}