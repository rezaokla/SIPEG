# Production Checklist Pegawaiku Laravel

## Wajib sebelum deploy
- Copy scaffold ini ke project Laravel asli.
- Daftarkan `RoleMiddleware` di `app/Http/Kernel.php` sebagai alias `role`.
- Pastikan `AuthServiceProvider` dimuat oleh aplikasi.
- Atur `.env` untuk MySQL, APP_KEY, APP_URL, MAIL, dan SESSION.
- Jalankan `php artisan migrate --seed`.
- Ubah password default akun seed di environment non-demo.

## Disarankan
- Tambahkan package export Excel/PDF.
- Tambahkan chart dashboard/laporan.
- Tambahkan request class untuk validasi per modul.
- Tambahkan feature test untuk login, user, pegawai, cuti, dan program.
- Gunakan queue untuk notifikasi email jika nanti dibutuhkan.

## Keamanan
- Gunakan HTTPS.
- Batasi akses debug pada production.
- Gunakan password policy yang lebih kuat.
- Tambahkan throttling login.
- Audit controller agar seluruh `authorize()` memakai policy resmi.
