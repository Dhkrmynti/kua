# Deploy ke VPS (Ubuntu 24.04)

Panduan ini untuk VPS baru dengan **Ubuntu 24.04**, RAM minimal **1 GB**, memakai **Nginx + PHP 8.3-FPM + SQLite**.
Waktu yang dibutuhkan sekitar 20–30 menit.

> Semua perintah dijalankan di VPS lewat SSH, kecuali yang ditandai **[Laptop]**.

## 0. Persiapan

- Domain/subdomain (mis. `demo-kua.domainanda.com`) dengan **A record** mengarah ke IP VPS.
  Tanpa domain tetap bisa diakses lewat `http://IP_VPS`, tapi tanpa HTTPS.
- Akses SSH ke VPS.

## 1. Pasang paket server

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx git unzip curl certbot python3-certbot-nginx \
  php8.3-fpm php8.3-cli php8.3-sqlite3 php8.3-mysql php8.3-mbstring \
  php8.3-xml php8.3-curl php8.3-zip php8.3-bcmath php8.3-intl

# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Node.js 22 untuk build frontend (Tailwind 4 butuh Node ≥ 20; versi apt bawaan terlalu lama)
curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
sudo apt install -y nodejs

# Firewall
sudo ufw allow OpenSSH && sudo ufw allow 'Nginx Full' && sudo ufw --force enable
```

## 2. Unggah kode

**Opsi A — lewat Git (disarankan, update jadi mudah)**

**[Laptop]** buat repo *private* di GitHub/GitLab, lalu:

```bash
cd "D:\PROJEK TOKEN\kua"
git init
git add .
git commit -m "Versi demo"
git branch -M main
git remote add origin https://github.com/USERNAME/kua.git
git push -u origin main
```

`.env`, `vendor/`, `node_modules/`, dan database **tidak** ikut ter-push (sudah diatur di `.gitignore`).

Di VPS:

```bash
sudo mkdir -p /var/www/kua && sudo chown $USER:www-data /var/www/kua
git clone https://github.com/USERNAME/kua.git /var/www/kua
```

**Opsi B — tanpa Git**

**[Laptop]** kompres folder proyek **tanpa** `vendor`, `node_modules`, `.env`, dan `database/database.sqlite`, lalu:

```bash
scp kua.zip user@IP_VPS:/tmp/
```

Di VPS:

```bash
sudo mkdir -p /var/www/kua && sudo chown $USER:www-data /var/www/kua
unzip /tmp/kua.zip -d /var/www/kua
```

## 3. Konfigurasi `.env` produksi

```bash
cd /var/www/kua
cp .env.example .env
nano .env
```

Ubah baris berikut:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://demo-kua.domainanda.com

LOG_LEVEL=error
SESSION_SECURE_COOKIE=true      # hapus baris ini bila belum pakai HTTPS

KUA_NAMA="KUA Kecamatan ......"
KUA_KABUPATEN="Kabupaten ......"
KUA_ALAMAT="..."
KUA_TELEPON="..."
KUA_EMAIL="..."

DEMO_PASSWORD=GantiDenganSandiKuat123
```

> ⚠️ `APP_DEBUG=false` **wajib**. Bila `true`, pesan error menampilkan isi `.env` dan potongan kode ke pengunjung.

## 4. Pasang aplikasi

```bash
cd /var/www/kua
composer install --no-dev --optimize-autoloader
php artisan key:generate
touch database/database.sqlite
php artisan migrate --force --seed    # --seed membuat akun & data demo
bash deploy/deploy.sh                 # build frontend, cache, dan hak akses
```

## 5. Konfigurasi PHP & Nginx

```bash
# Batas upload (pengajuan nikah bisa sampai 10 berkas x 2 MB)
sudo cp deploy/php-kua.ini /etc/php/8.3/fpm/conf.d/99-kua.ini
sudo systemctl restart php8.3-fpm

# Nginx
sudo cp deploy/nginx.conf /etc/nginx/sites-available/kua
sudo sed -i 's/DOMAIN_ANDA/demo-kua.domainanda.com/' /etc/nginx/sites-available/kua
sudo ln -s /etc/nginx/sites-available/kua /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t && sudo systemctl reload nginx
```

## 6. HTTPS gratis (Let's Encrypt)

```bash
sudo certbot --nginx -d demo-kua.domainanda.com --redirect -m email@anda.com --agree-tos -n
```

Sertifikat diperpanjang otomatis.

## 7. Cek

Buka `https://demo-kua.domainanda.com`, lalu:

- [ ] Login dengan `admin@kua.test` / sandi `DEMO_PASSWORD`
- [ ] Buat pengajuan nikah sebagai `warga@kua.test` dengan mengunggah beberapa PDF
- [ ] Buka dokumen dari akun petugas
- [ ] Ubah status ke *Dijadwalkan*, lalu cek di akun warga dan halaman **Lacak**

## Update kode berikutnya

```bash
# [Laptop]
git add . && git commit -m "perubahan" && git push

# VPS
cd /var/www/kua && bash deploy/deploy.sh
```

## Reset data demo (mis. sebelum presentasi)

```bash
cd /var/www/kua
php artisan migrate:fresh --seed --force
sudo rm -rf storage/app/private/dokumen storage/app/private/pengaduan
```

## Memakai MySQL (opsional)

```bash
sudo apt install -y mysql-server
sudo mysql -e "CREATE DATABASE kua; CREATE USER 'kua'@'localhost' IDENTIFIED BY 'SandiDB'; GRANT ALL ON kua.* TO 'kua'@'localhost';"
```

Di `.env`: `DB_CONNECTION=mysql`, `DB_DATABASE=kua`, `DB_USERNAME=kua`, `DB_PASSWORD=SandiDB`, lalu
`php artisan config:clear && php artisan migrate:fresh --seed --force && php artisan optimize`.

## Masalah umum

| Gejala | Penyebab & solusi |
|---|---|
| **500 Server Error** | Lihat `storage/logs/laravel.log`. Paling sering: hak akses, jalankan ulang `bash deploy/deploy.sh` |
| **413 Request Entity Too Large** saat upload | `client_max_body_size` Nginx belum terpasang (langkah 5) |
| Tampilan polos tanpa CSS | `public/build` belum ada. Jalankan `npm ci && npm run build` |
| Perubahan `.env` tidak berpengaruh | Konfigurasi ter-cache. Jalankan `php artisan optimize:clear && php artisan optimize` |
| `attempt to write a readonly database` | Folder `database/` harus bisa ditulis www-data: `sudo chown -R $USER:www-data database && sudo chmod -R ug+rwX database` |
| Link mengarah ke `http://` padahal sudah HTTPS | `APP_URL` belum diubah ke `https://`, lalu jalankan `php artisan optimize` |
