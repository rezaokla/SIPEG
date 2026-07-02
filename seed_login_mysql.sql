USE pegawaiku;

INSERT INTO users (nama, email, password, role, created_at, updated_at)
VALUES
('Administrator', 'admin@pegawaiku.local', '$2y$12$b9x0x4nP1B3vLQk1h4gXVO2CjM8k3F6uM4vWv3Jd8A9vP9D7mW6gW', 'admin', NOW(), NOW()),
('Pimpinan', 'pimpinan@pegawaiku.local', '$2y$12$b9x0x4nP1B3vLQk1h4gXVO2CjM8k3F6uM4vWv3Jd8A9vP9D7mW6gW', 'pimpinan', NOW(), NOW()),
('Pegawai Contoh', 'pegawai@pegawaiku.local', '$2y$12$b9x0x4nP1B3vLQk1h4gXVO2CjM8k3F6uM4vWv3Jd8A9vP9D7mW6gW', 'pegawai', NOW(), NOW());

-- Password placeholder hash; lebih aman gunakan php artisan db:seed agar hash valid konsisten.
