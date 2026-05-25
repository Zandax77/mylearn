<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_import_users_without_password_and_they_are_active_with_default_password(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $csvContent = "name,email,role,nama_kelas\n";
        $csvContent .= "Siti Guru,guru.siti@sekolah.com,guru,\n";
        $csvContent .= "Budi Siswa,siswa.budi@sekolah.com,siswa,10 IPA 1\n";

        $file = UploadedFile::fake()->createWithContent('template_pengguna.csv', $csvContent);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.import.users'), [
                'file_users' => $file,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        
        $this->assertDatabaseHas('users', [
            'email' => 'guru.siti@sekolah.com',
            'role' => 'guru',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'siswa.budi@sekolah.com',
            'role' => 'siswa',
            'is_active' => true,
        ]);

        $guru = User::where('email', 'guru.siti@sekolah.com')->first();
        $this->assertTrue(Hash::check('password', $guru->password));

        $siswa = User::where('email', 'siswa.budi@sekolah.com')->first();
        $this->assertTrue(Hash::check('password', $siswa->password));
    }
}
