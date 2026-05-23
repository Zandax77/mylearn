<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminBootstrapper
{
    public static function ensureSuperAdmin(): void
    {
        // Kalau tabel belum ada / belum migrate
        if (!Schema::hasTable('users')) {
            return;
        }

        // Jika sudah ada user, tidak perlu buat lagi
        if (User::query()->exists()) {
            return;
        }

        $email = 'admin@lms.com';

        // Cegah duplikasi bila ada race condition
        User::query()->firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );
    }
}

