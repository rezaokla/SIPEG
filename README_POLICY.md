Policy Laravel yang disarankan untuk tahap berikutnya:
- UserPolicy
- PegawaiPolicy
- CutiPolicy
- ProgramPolicy
- PrestasiPolicy

Gunakan `php artisan make:policy` lalu daftarkan di AuthServiceProvider.
Untuk versi saat ini, kontrol akses utama masih memakai role check di controller dan middleware.
