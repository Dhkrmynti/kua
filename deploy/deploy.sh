#!/usr/bin/env bash
# Jalankan di VPS dari folder aplikasi: bash deploy/deploy.sh
# Aman dijalankan berulang setiap kali ada pembaruan kode.
set -euo pipefail

cd "$(dirname "$0")/.."

echo "→ Mode maintenance"
php artisan down --retry=15 || true

if [ -d .git ]; then
    echo "→ Tarik kode terbaru"
    git pull --ff-only
fi

echo "→ Dependensi PHP"
composer install --no-dev --optimize-autoloader --no-interaction

if command -v npm >/dev/null 2>&1; then
    echo "→ Build frontend"
    npm ci --no-audit --no-fund
    npm run build
else
    echo "→ npm tidak ada, memakai public/build yang diunggah"
fi

[ -f database/database.sqlite ] || touch database/database.sqlite

echo "→ Migrasi database"
php artisan migrate --force

echo "→ Cache konfigurasi"
php artisan optimize

echo "→ Hak akses"
sudo chown -R "$USER":www-data storage bootstrap/cache database
sudo chmod -R ug+rwX storage bootstrap/cache database

php artisan up
echo "✓ Selesai"
