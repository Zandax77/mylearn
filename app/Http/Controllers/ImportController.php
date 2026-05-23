<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    public function index()
    {
        return view('admin.import.index');
    }

    public function importUsers(Request $request)
    {
        $request->validate([
            'file_users' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $fileHandle = fopen($request->file('file_users')->getRealPath(), 'r');
        $header = fgetcsv($fileHandle);
        
        if (!$header) {
            return back()->with('error', 'File CSV kosong.');
        }

        // Sanitize header (remove BOM if present)
        $header[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header[0]);
        $expectedHeader = ['name', 'email', 'password', 'role', 'nama_kelas'];
        
        if ($header !== $expectedHeader) {
            fclose($fileHandle);
            return back()->with('error', 'Format header CSV tidak sesuai! Harus: name, email, password, role, nama_kelas');
        }

        $imported = 0;
        $skipped = 0;

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($fileHandle)) !== false) {
                if (array_filter($row) === []) continue; // Skip empty rows
                if (count($row) != 5) continue;
                
                list($name, $email, $password, $role, $nama_kelas) = $row;
                $email = trim($email);
                
                $kelas_id = null;
                if (!empty(trim($nama_kelas))) {
                    $kelas = Kelas::firstOrCreate(['nama_kelas' => trim($nama_kelas)]);
                    $kelas_id = $kelas->id;
                }
                
                if (User::where('email', $email)->exists()) {
                    $skipped++;
                    continue;
                }

                User::create([
                    'name' => trim($name),
                    'email' => $email,
                    'password' => Hash::make(trim($password)),
                    'role' => strtolower(trim($role)),
                    'kelas_id' => $kelas_id,
                ]);
                $imported++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($fileHandle);
            return back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }

        fclose($fileHandle);
        return back()->with('success', "Import selesai. Berhasil ditambahkan: $imported, Dilewati (Email ganda): $skipped");
    }

    public function importKelas(Request $request)
    {
        $request->validate([
            'file_kelas' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $fileHandle = fopen($request->file('file_kelas')->getRealPath(), 'r');
        $header = fgetcsv($fileHandle);
        
        if (!$header) {
            return back()->with('error', 'File CSV kosong.');
        }

        // Sanitize header (remove BOM if present)
        $header[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header[0]);
        $expectedHeader = ['nama_kelas', 'wali_kelas'];
        
        if ($header !== $expectedHeader) {
            fclose($fileHandle);
            return back()->with('error', 'Format header CSV tidak sesuai! Harus: nama_kelas, wali_kelas');
        }

        $imported = 0;
        $skipped = 0;

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($fileHandle)) !== false) {
                if (array_filter($row) === []) continue;
                if (count($row) != 2) continue;
                
                list($nama_kelas, $wali_kelas) = $row;
                $nama_kelas = trim($nama_kelas);
                
                if (Kelas::where('nama_kelas', $nama_kelas)->exists()) {
                    $skipped++;
                    continue;
                }

                Kelas::create([
                    'nama_kelas' => $nama_kelas,
                    'wali_kelas' => trim($wali_kelas),
                ]);
                $imported++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($fileHandle);
            return back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }

        fclose($fileHandle);
        return back()->with('success', "Import kelas selesai. Berhasil ditambahkan: $imported, Dilewati (Kelas ganda): $skipped");
    }

    public function downloadTemplateUsers()
    {
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=template_pengguna.csv',
        ];
        
        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['name', 'email', 'password', 'role', 'nama_kelas']);
            fputcsv($file, ['Siti Guru', 'guru.siti@sekolah.com', 'password123', 'guru', '']);
            fputcsv($file, ['Budi Siswa', 'siswa.budi@sekolah.com', 'password123', 'siswa', '10 IPA 1']);
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function downloadTemplateKelas()
    {
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=template_kelas.csv',
        ];
        
        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['nama_kelas', 'wali_kelas']);
            fputcsv($file, ['10 IPA 1', 'Bapak Ahmad']);
            fputcsv($file, ['11 IPS 2', 'Ibu Nurul']);
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function importMapel(Request $request)
    {
        $request->validate([
            'file_mapel' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $fileHandle = fopen($request->file('file_mapel')->getRealPath(), 'r');
        $header = fgetcsv($fileHandle);
        
        if (!$header) {
            return back()->with('error', 'File CSV kosong.');
        }

        // Sanitize header (remove BOM if present)
        $header[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header[0]);
        $expectedHeader = ['nama_mapel', 'guru_email'];
        
        if ($header !== $expectedHeader) {
            fclose($fileHandle);
            return back()->with('error', 'Format header CSV tidak sesuai! Harus: nama_mapel, guru_email');
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            $rowNumber = 2;
            while (($row = fgetcsv($fileHandle)) !== false) {
                if (array_filter($row) === []) {
                    $rowNumber++;
                    continue;
                }
                if (count($row) != 2) {
                    $rowNumber++;
                    continue;
                }
                
                list($nama_mapel, $guru_email) = $row;
                $nama_mapel = trim($nama_mapel);
                $guru_email = trim($guru_email);
                
                // Cek apakah nama mapel sudah ada
                if (Mapel::where('nama_mapel', $nama_mapel)->exists()) {
                    $skipped++;
                    $rowNumber++;
                    continue;
                }

                $guru_id = null;
                // Jika guru_email tidak kosong, cari guru berdasarkan email
                if (!empty($guru_email)) {
                    $guru = User::where('email', $guru_email)
                        ->where('role', 'guru')
                        ->first();
                    
                    if (!$guru) {
                        $errors[] = "Baris $rowNumber: Guru dengan email '$guru_email' tidak ditemukan";
                        $rowNumber++;
                        continue;
                    }
                    $guru_id = $guru->id;
                }

                Mapel::create([
                    'nama_mapel' => $nama_mapel,
                    'guru_id' => $guru_id,
                ]);
                $imported++;
                $rowNumber++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($fileHandle);
            return back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }

        fclose($fileHandle);
        
        $message = "Import mapel selesai. Berhasil ditambahkan: $imported, Dilewati (Mapel ganda): $skipped";
        if (!empty($errors)) {
            $message .= ". Errors: " . implode("; ", $errors);
            return back()->with('warning', $message);
        }
        
        return back()->with('success', $message);
    }

    public function downloadTemplateMapel()
    {
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=template_mapel.csv',
        ];
        
        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['nama_mapel', 'guru_email']);
            fputcsv($file, ['Matematika', 'guru1@lms.com']);
            fputcsv($file, ['Bahasa Indonesia', 'guru2@lms.com']);
            fputcsv($file, ['Fisika', '']);
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
