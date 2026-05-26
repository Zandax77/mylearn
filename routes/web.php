<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\ImportController;
use Illuminate\Support\Facades\Route;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Support\Facades\Schema;

Route::middleware(['web'])->group(function () {
    // Pastikan super admin otomatis dibuat saat aplikasi baru diaktifkan / belum ada user
    Route::get('/', [App\Http\Controllers\LandingActivityController::class, 'index']);
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('kelas', KelasController::class);
        Route::resource('mapel', MapelController::class);

        Route::get('import', [ImportController::class, 'index'])->name('import.index');
        Route::post('import/users', [ImportController::class, 'importUsers'])->name('import.users');
        Route::post('import/kelas', [ImportController::class, 'importKelas'])->name('import.kelas');
        Route::post('import/mapel', [ImportController::class, 'importMapel'])->name('import.mapel');
        Route::get('import/template/users', [ImportController::class, 'downloadTemplateUsers'])->name('import.template.users');
        Route::get('import/template/kelas', [ImportController::class, 'downloadTemplateKelas'])->name('import.template.kelas');
        Route::get('import/template/mapel', [ImportController::class, 'downloadTemplateMapel'])->name('import.template.mapel');

        Route::get('monitoring/guru', [App\Http\Controllers\AdminMonitoringController::class, 'index'])->name('monitoring.guru');

        Route::get('users', [App\Http\Controllers\AdminUserController::class, 'index'])->name('users.index');
        Route::post('users/{user}/reset-password', [App\Http\Controllers\AdminUserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('users/{user}/toggle-status', [App\Http\Controllers\AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');

        Route::get('settings', [App\Http\Controllers\AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [App\Http\Controllers\AdminSettingController::class, 'update'])->name('settings.update');
    });

    // Guru Routes
    Route::middleware('role:guru')->prefix('guru')->name('guru.')->group(function () {
        Route::get('mapel', [App\Http\Controllers\GuruMapelController::class, 'index'])->name('mapel.index');
        Route::get('mapel/{id}/assign', [App\Http\Controllers\GuruMapelController::class, 'edit'])->name('mapel.assign');
        Route::put('mapel/{id}/assign', [App\Http\Controllers\GuruMapelController::class, 'update'])->name('mapel.update');

        Route::get('mapel/{mapel_id}/babs', [App\Http\Controllers\BabController::class, 'index'])->name('babs.index');
        Route::post('mapel/{mapel_id}/babs', [App\Http\Controllers\BabController::class, 'store'])->name('babs.store');
        Route::put('babs/{bab}', [App\Http\Controllers\BabController::class, 'update'])->name('babs.update');
        Route::delete('babs/{bab}', [App\Http\Controllers\BabController::class, 'destroy'])->name('babs.destroy');

        Route::get('mapel/{mapel_id}/bank-soal', [App\Http\Controllers\BankSoalController::class, 'index'])->name('bank_soal.index');
        Route::get('bank-soal/template', [App\Http\Controllers\BankSoalController::class, 'template'])->name('bank_soal.template');
        Route::post('mapel/{mapel_id}/bank-soal/upload', [App\Http\Controllers\BankSoalController::class, 'upload'])->name('bank_soal.upload');
        Route::delete('bank-soal/{id}', [App\Http\Controllers\BankSoalController::class, 'destroy'])->name('bank_soal.destroy');

        Route::get('mapel/{mapel_id}/progress', [App\Http\Controllers\SiswaProgressController::class, 'index'])->name('mapel.progress');
        Route::get('mapel/{mapel_id}/progress/{siswa_id}', [App\Http\Controllers\SiswaProgressController::class, 'show'])->name('mapel.progress.detail');

        Route::resource('ujians', App\Http\Controllers\UjianController::class);
        Route::resource('ujians.soals', App\Http\Controllers\SoalController::class)->shallow();

        Route::resource('materi', MateriController::class);
        Route::resource('tugas', TugasController::class);
        Route::post('tugas/{tugas}/nilai/{pengumpulan}', [TugasController::class, 'nilai'])->name('tugas.nilai');
    });

    // Siswa Routes
    Route::middleware('role:siswa')->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('mapel', [App\Http\Controllers\SiswaMapelController::class, 'index'])->name('mapel.index');
        Route::get('mapel/{mapel_id}', [App\Http\Controllers\SiswaMapelController::class, 'show'])->name('mapel.show');

        Route::get('ujian/{ujian_id}', [App\Http\Controllers\SiswaUjianController::class, 'show'])->name('ujian.show');
        Route::post('ujian/{ujian_id}/submit', [App\Http\Controllers\SiswaUjianController::class, 'submit'])->name('ujian.submit');

        Route::get('materi/{id}/view', [App\Http\Controllers\MateriController::class, 'viewMateri'])->name('materi.view');

        // Siswa hanya boleh melihat daftar tugas dan detail tugas (tidak bisa membuat/edit/hapus)
        Route::get('tugas', [TugasController::class, 'index'])->name('tugas.index');
        Route::get('tugas/{tugas}', [TugasController::class, 'show'])->name('tugas.show');
        Route::post('tugas/{tugas}/kumpul', [TugasController::class, 'kumpul'])->name('tugas.kumpul');
        Route::get('tugas/{tugas}/jawaban/view', [TugasController::class, 'viewJawaban'])->name('tugas.jawaban.view');

    });
});

require __DIR__.'/auth.php';
