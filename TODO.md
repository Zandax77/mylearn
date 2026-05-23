# TODO

## Dummy Data untuk Grafik Dashboard

- [x] Update seeder `database/seeders/LmsSeeder.php` dengan dummy data yang lebih besar dan tersebar untuk 7 hari terakhir.
- [x] Jalankan `php artisan migrate:fresh --seed` dan pastikan seeder sukses.

## Issue: grafik progress guru dan siswa tidak terlihat di halaman admin

- [ ] Verifikasi apakah variabel `$guruActiveCountsByDay`, `$siswaActiveCountsByDay`, `$labels7d` masuk ke view (cek error JS/HTML).
- [ ] Pastikan Chart.js render: cek console error (mis. `document.getElementById(...)` null / Chart.js syntax error).
- [ ] Perbaiki `resources/views/dashboard/admin.blade.php` jika ada bug pembuatan chart line (misalnya blok `new Chart(guruEl, ...)` tertutup/kurang indent atau missing `});`).

