# Aurify — Step-by-Step Walkthrough

Dokumen ini menjelaskan step by step cara merekonstruksi aplikasi Aurify dari nol, persis seperti yang dilakukan dari awal.

---

## Daftar Isi

- [Prerequisites](#prerequisites)
- [Phase 1 — Setup Laravel Project](#phase-1--setup-laravel-project)
- [Phase 2 — Setup Frontend (React + Inertia + Tailwind)](#phase-2--setup-frontend-react--inertia--tailwind)
- [Phase 3 — Konfigurasi Inertia](#phase-3--konfigurasi-inertia)
- [Phase 4 — Landing Page Components](#phase-4--landing-page-components)
- [Phase 5 — Database & Lead Form](#phase-5--database--lead-form)
- [Phase 6 — Filament Admin Panel](#phase-6--filament-admin-panel)
- [Phase 7 — Activity Logging](#phase-7--activity-logging)
- [Phase 8 — Security](#phase-8--security)
- [Phase 9 — Docker Deployment](#phase-9--docker-deployment)
- [Phase 10 — Tambahan & Penyempurnaan](#phase-10--tambahan--penyempurnaan)

---

## Prerequisites

Pastikan tools berikut sudah terinstall:

- **PHP 8.2+** dengan extensions: `pdo_mysql`, `mbstring`, `intl`, `xml`, `zip`, `curl`, `bcmath`
- **Composer 2**
- **Node.js 18+** dan **npm**
- **MySQL 8.0**
- **Git**

---

## Phase 1 — Setup Laravel Project

### 1.1 Buat project Laravel baru

```bash
composer create-project laravel/laravel aurify
cd aurify
```

> Laravel 12 akan otomatis ter-install sebagai versi terbaru.

### 1.2 Konfigurasi `.env`

Salin `.env.example` ke `.env`, lalu sesuaikan:

```env
APP_NAME=Aurify
APP_URL=http://localhost:8000
ASSET_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aurify
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

BCRYPT_ROUNDS=12

SESSION_DRIVER=database
SESSION_ENCRYPT=false
SESSION_SECURE_COOKIE=false
SESSION_SAME_SITE=
SESSION_HTTP_ONLY=

CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_FROM_ADDRESS="hello@aurify.id"
MAIL_FROM_NAME="${APP_NAME}"

TRUSTED_PROXIES="*"
```

### 1.3 Generate application key

```bash
php artisan key:generate
```

---

## Phase 2 — Setup Frontend (React + Inertia + Tailwind)

### 2.1 Install package frontend

```bash
npm install @inertiajs/react @vitejs/plugin-react react react-dom react-scroll
npm install --save-dev @tailwindcss/vite tailwindcss vite laravel-vite-plugin axios
```

### 2.2 Install Inertia server-side (PHP)

```bash
composer require inertiajs/inertia-laravel
```

### 2.3 Konfigurasi `vite.config.js`

Isi file `vite.config.js` dengan konfigurasi berikut:

```js
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import react from "@vitejs/plugin-react";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.jsx"],
            refresh: true,
        }),
        tailwindcss(),
        react(),
    ],
    server: {
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },
});
```

---

## Phase 3 — Konfigurasi Inertia

### 3.1 Buat root blade template

Hapus `resources/views/welcome.blade.php`, lalu buat `resources/views/app.blade.php`:

- Import Google Fonts: **Cormorant** (heading) dan **Manrope** (body)
- Tambahkan `@viteReactRefresh`, `@vite(...)`, dan `@inertiaHead` di `<head>`
- Tambahkan `@inertia` di `<body>`
- Set `lang="id"` dan meta description

### 3.2 Buat entrypoint React

Buat `resources/js/app.jsx`:

- Import `createInertiaApp` dari `@inertiajs/react`
- Import `createRoot` dari `react-dom/client`
- Gunakan `import.meta.glob` untuk auto-load semua Pages
- Wrap dengan `createRoot().render()`

### 3.3 Middleware Inertia

File `app/Http/Middleware/HandleInertiaRequests.php` sudah auto-dibuat saat install. Edit method `share()` untuk meneruskan flash message ke frontend:

```php
public function share(Request $request): array
{
    return [
        ...parent::share($request),
        'flash' => [
            'success' => fn() => $request->session()->get('success'),
            'error'   => fn() => $request->session()->get('error'),
        ],
    ];
}
```

### 3.4 Daftarkan middleware di `bootstrap/app.php`

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->web(append: [
        \App\Http\Middleware\HandleInertiaRequests::class,
        \App\Http\Middleware\SecurityHeaders::class,
    ]);
    $middleware->trustProxies(at: '*');
})
```

---

## Phase 4 — Landing Page Components

### 4.1 Buat custom Tailwind theme di `resources/css/app.css`

Tambahkan tema custom dengan:

- **Color scale**: `gold-50` s/d `gold-900` dan `dark-50` s/d `dark-900`
- **Custom utilities**: `text-gold-gradient`, `text-gold-shine`, `bg-gold-btn`, `glow-gold`, `gold-line`
- **Keyframe animations**: `gold-shimmer`, `float`, `fade-in-up`, `pulse-gold`
- **Custom scrollbar** dengan warna emas
- **Canvas positioning** untuk particle background

### 4.2 Buat folder struktur Pages dan Components

```
resources/js/
├── Pages/
│   └── Landing.jsx
└── Components/
    ├── Navbar.jsx
    ├── Hero.jsx
    ├── LeadForm.jsx
    ├── Features.jsx
    ├── Pricing.jsx
    ├── Testimonials.jsx
    ├── CtaFaq.jsx
    ├── Footer.jsx
    └── GoldParticles.jsx
```

### 4.3 Buat `Landing.jsx`

Page utama yang mengimpor dan menyusun semua komponen:
`GoldParticles` → `Navbar` → `Hero` → `LeadForm` → `Features` → `Pricing` → `Testimonials` → `CtaFaq` → `Footer`

### 4.4 Detail tiap komponen

| File                | Deskripsi                                                                                                                                            |
| ------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------- |
| `GoldParticles.jsx` | Canvas-based animasi 35 partikel emas mengambang dengan glow effect                                                                                  |
| `Navbar.jsx`        | Fixed navbar dengan scroll detection, hamburger mobile, smooth scroll ke section, CTA "Daftar Gratis"                                                |
| `Hero.jsx`          | Hero section dengan dashboard mockup (browser chrome palsu, stats, chart bar, daftar transaksi), trust badges                                        |
| `LeadForm.jsx`      | Form 4 field (nama, whatsapp, email, lembaga), POST ke `/leads` via Inertia `useForm`, success state dengan animasi checkmark, tampil error validasi |
| `Features.jsx`      | 4 feature card (Budgeting Emas, Tracking Pengeluaran, Invoice & Laporan, AI Prediksi Cash Flow) dengan IntersectionObserver scroll animation         |
| `Pricing.jsx`       | 3-tier pricing (Starter/Gratis, Professional/Rp99.000, Enterprise/Custom) dengan popular badge                                                       |
| `Testimonials.jsx`  | 4 testimoni dari pengguna UMKM fiktif dengan star rating dan avatar inisial                                                                          |
| `CtaFaq.jsx`        | CTA banner + accordion FAQ 5 item                                                                                                                    |
| `Footer.jsx`        | 4-kolom footer: brand, navigasi, fitur, info kontak                                                                                                  |

### 4.5 Konfigurasi route awal di `routes/web.php`

```php
Route::get('/', function () {
    return Inertia::render('Landing');
});
```

---

## Phase 5 — Database & Lead Form

### 5.1 Buat database MySQL

```sql
CREATE DATABASE aurify;
```

### 5.2 Buat migration untuk tabel `leads`

```bash
php artisan make:migration create_leads_table
```

Isi `up()` di migration:

```php
Schema::create('leads', function (Blueprint $table) {
    $table->id();
    $table->string('nama');
    $table->string('whatsapp');
    $table->string('email');
    $table->string('lembaga')->nullable();
    $table->timestamps();
});
```

### 5.3 Buat model `Lead`

```bash
php artisan make:model Lead
```

Set `$fillable`:

```php
protected $fillable = ['nama', 'whatsapp', 'email', 'lembaga'];
```

### 5.4 Buat `LeadController`

```bash
php artisan make:controller LeadController
```

Method `store()` berisi:

- Validasi: `nama` required, `whatsapp` regex angka/+/-, `email` required, `lembaga` nullable
- Sanitasi: loop `strip_tags()` dan `trim()` pada semua nilai string
- Simpan ke DB dengan `Lead::create()`
- Return `back()->with('success', '...')`

### 5.5 Tambahkan rate limiter di `AppServiceProvider`

Di method `boot()`:

```php
RateLimiter::for('leads', function (Request $request) {
    return Limit::perMinute(5)->by($request->ip());
});
```

### 5.6 Daftarkan route POST di `routes/web.php`

```php
Route::post('/leads', [\App\Http\Controllers\LeadController::class, 'store'])
    ->middleware('throttle:leads');
```

### 5.7 Jalankan migrations

```bash
php artisan migrate
```

---

## Phase 6 — Filament Admin Panel

### 6.1 Install Filament

```bash
composer require filament/filament:"^3.3"
php artisan filament:install --panels
```

> Saat ditanya ID panel, isi: `admin`

### 6.2 Update `User` model

- Implement interface `FilamentUser`
- Tambahkan method `canAccessPanel(Panel $panel): bool` → return `true`

### 6.3 Seed admin users

Edit `database/seeders/DatabaseSeeder.php`, buat 2 user admin:

| Email          | Password | Nama      |
| -------------- | -------- | --------- |
| admin1@wafa.id | admin123 | Admin One |
| admin2@wafa.id | admin123 | Admin Two |

```bash
php artisan db:seed
```

### 6.4 Kustomisasi `AdminPanelProvider`

File ada di `app/Providers/Filament/AdminPanelProvider.php`. Tambahkan konfigurasi:

```php
->brandName('Aurify')
->favicon(asset('favicon.ico'))
->colors([
    'primary' => [
        50  => '#FFF9E6',
        100 => '#F5DEB3',
        // ... gold scale lengkap s/d 950
        500 => '#FFD700',
        800 => '#8B4513',
    ],
    'gray' => [
        // ... dark scale #0A0A0A s/d #F9FAFB
    ],
])
->font('Manrope')
->darkMode(true, isForced: true)
->sidebarCollapsibleOnDesktop()
->maxContentWidth('full')
```

### 6.5 Daftarkan `AdminPanelProvider` di `bootstrap/providers.php`

```php
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
];
```

### 6.6 Buat `LeadResource`

Buat file `app/Filament/Resources/LeadResource.php` secara manual (tanpa artisan agar lebih terkontrol):

Konfigurasi utama:

- `$model = Lead::class`
- `$navigationIcon = 'heroicon-o-user-group'`
- `$navigationGroup = 'Lead Management'`
- **Form**: `Section` dengan 4 field (`nama`, `whatsapp`, `email`, `lembaga`) dalam 2 kolom
- **Table**: kolom ID, nama (bold), whatsapp (copyable), email (copyable), lembaga (toggleable), created_at; sort default desc
- **Filters**: `has_organization`, `created_today`, `created_this_week`
- **Actions**: View, Edit, Delete per baris; BulkDelete
- `getNavigationBadge()` → tampilkan jumlah total leads dengan warna `warning`

Buat folder `app/Filament/Resources/LeadResource/Pages/` dan buat 3 file:

**`ListLeads.php`** — extends `ListRecords`, header action `CreateAction`

**`CreateLead.php`** — extends `CreateRecord`, override `getRedirectUrl()` ke index

**`EditLead.php`** — extends `EditRecord`, header action `DeleteAction`, override `getRedirectUrl()` ke index

### 6.7 Buat `ActivityLogResource`

Buat `app/Filament/Resources/ActivityLogResource.php`:

- `$model = Activity::class` (dari Spatie)
- `$navigationGroup = 'System'`
- `canCreate()` → return `false` (read-only)
- **Table**: kolom ID, log_name (badge warning), description, subject_type (class_basename), subject_id, causer.name, event (badge: created=success, updated=info, deleted=danger), created_at
- **Filters**: SelectFilter by `event`, SelectFilter by `log_name`
- **Actions**: hanya `ViewAction`, tidak ada bulk action
- `getNavigationBadge()` → total activity count

Buat `app/Filament/Resources/ActivityLogResource/Pages/ListActivityLogs.php` — extends `ListRecords`

### 6.8 Buat Dashboard Widgets

**`app/Filament/Widgets/StatsOverview.php`** — extends `StatsOverviewWidget`:

- 4 stats: Total Leads, Leads Today, Leads This Week, Activity Logs
- Total Leads dilengkapi sparkline chart 7 hari terakhir

**`app/Filament/Widgets/LeadsChart.php`** — extends `ChartWidget`:

- Heading: `'Leads Over Time'`
- Type: `'line'`
- Data: leads per hari dalam 30 hari terakhir
- Border color: `#DAA520` (gold), background: `rgba(218,165,32,0.1)`

---

## Phase 7 — Activity Logging

### 7.1 Install Spatie Laravel Activity Log

```bash
composer require spatie/laravel-activitylog:"^4.9"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan migrate
```

### 7.2 Tambahkan `LogsActivity` trait ke model

**`Lead` model:**

```php
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Lead extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama', 'whatsapp', 'email', 'lembaga'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Lead has been {$eventName}");
    }
}
```

**`User` model** — tambahkan trait yang sama, `logOnly(['name', 'email'])`

### 7.3 Buat `AuthActivityLogger` listener

Buat `app/Listeners/AuthActivityLogger.php` dengan 2 method:

- `handleLogin(Login $event)` — log ke channel `'auth'`, catat IP dan user_agent
- `handleLogout(Logout $event)` — log ke channel `'auth'`, catat IP

### 7.4 Buat `EventServiceProvider`

Buat `app/Providers/EventServiceProvider.php` dan daftarkan listener:

```php
public function boot(): void
{
    Event::listen(Login::class, [AuthActivityLogger::class, 'handleLogin']);
    Event::listen(Logout::class, [AuthActivityLogger::class, 'handleLogout']);
}
```

Daftarkan ke `bootstrap/providers.php`.

---

## Phase 8 — Security

### 8.1 Buat `SecurityHeaders` middleware

Buat `app/Http/Middleware/SecurityHeaders.php`:

```php
public function handle(Request $request, Closure $next): Response
{
    $response = $next($request);
    $response->headers->set('X-Content-Type-Options', 'nosniff');
    $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
    $response->headers->set('X-XSS-Protection', '1; mode=block');
    $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
    return $response;
}
```

Daftarkan di `bootstrap/app.php` (sudah dilakukan di Phase 3).

### 8.2 Buat `TrustProxies` middleware

Buat `app/Http/Middleware/TrustProxies.php`:

- Extend `Illuminate\Http\Middleware\TrustProxies`
- Set `$proxies = '*'`
- Set `$headers` dengan semua `HEADER_X_FORWARDED_*`

Daftarkan di `bootstrap/app.php` via `$middleware->trustProxies(at: '*')`.

### 8.3 Ringkasan semua security layer

| Layer              | Implementasi                                         |
| ------------------ | ---------------------------------------------------- |
| CSRF               | Laravel built-in, otomatis aktif untuk semua POST    |
| Rate Limiting      | 5 req/menit per IP pada `/leads`                     |
| Security Headers   | Middleware `SecurityHeaders` (X-Frame-Options, dll.) |
| Input Sanitization | `strip_tags()` + `trim()` di `LeadController`        |
| Password Hashing   | Bcrypt 12 rounds (`BCRYPT_ROUNDS=12` di `.env`)      |
| Admin Auth         | Filament session-based authentication                |
| Proxy Trust        | `TrustProxies` middleware untuk header forwarded     |

---

## Phase 9 — Docker Deployment

### 9.1 Buat `Dockerfile` (multi-stage build)

**Stage 1 — `frontend` (node:20-alpine):**

- Copy `package.json`, `package-lock.json`, `vite.config.js`, `resources/`
- `npm ci && npm run build`

**Stage 2 — `vendor` (composer:2):**

- Copy `composer.json`, `composer.lock`
- `composer install --no-dev --optimize-autoloader --ignore-platform-reqs`

**Stage 3 — `production` (php:8.3-fpm-alpine):**

- Install: `nginx`, `supervisor`, `icu-dev`, `oniguruma-dev`
- PHP extensions: `pdo_mysql`, `mbstring`, `intl`, `opcache`
- Copy app, kemudian copy hasil `--from=frontend` (public/build) dan `--from=vendor` (vendor)
- Set permission `www-data` untuk `storage/` dan `bootstrap/cache/`
- Hapus `node_modules`, `tests`, `.git`, `resources/js`, `resources/css`, `vite.config.js`, `package.json`, `.env`
- `EXPOSE 8000`
- `ENTRYPOINT ["sh", "/var/www/html/docker/entrypoint.sh"]`
- `CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]`

### 9.2 Buat `docker/nginx.conf`

```nginx
server {
    listen 8000 default_server;
    root /var/www/html/public;
    index index.php;
    server_tokens off;

    location / { try_files $uri $uri/ /index.php?$query_string; }
    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }
    location ~ /\.(?!well-known) { deny all; }
    # + security headers add_header, gzip on
}
```

### 9.3 Buat `docker/supervisord.conf`

Dua program: `php-fpm` dan `nginx`, keduanya log ke stdout/stderr, `autorestart=true`.

### 9.4 Buat `docker/php.ini`

```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.validate_timestamps=0
expose_php=Off
display_errors=Off
log_errors=On
memory_limit=256M
```

### 9.5 Buat `docker/entrypoint.sh`

Script yang dijalankan saat container start:

```sh
#!/bin/sh
set -e

# Buat .env dari .env.example jika belum ada
if [ ! -f .env ]; then cp .env.example .env; fi

# Generate key jika belum ada
if [ -z "$APP_KEY" ]; then php artisan key:generate --force; fi

# Cache untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migrate
php artisan migrate --force

# Seed jika users table kosong
USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null | tail -1)
if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
    php artisan db:seed --force
fi

exec "$@"
```

Jadikan executable:

```bash
chmod +x docker/entrypoint.sh
```

### 9.6 Buat `docker-compose.yml`

Dua service:

**`app`:**

- Build dari `Dockerfile` lokal
- Port: `8000:8000`
- Environment variables: APP, DB (DB_HOST=mysql), SESSION, CACHE, QUEUE
- `depends_on: mysql (condition: service_healthy)`
- Network: `aurify-network`

**`mysql`:**

- Image: `mysql:8.0`
- Port: `3306:3306`
- Volume: `mysql_data:/var/lib/mysql`
- Healthcheck: `mysqladmin ping`
- Network: `aurify-network`

### 9.7 Buat `.dockerignore`

```
.git
node_modules
vendor
storage/logs/*
storage/framework/cache/*
storage/framework/sessions/*
storage/framework/views/*
tests
.env
README.md
database/database.sqlite
```

### 9.8 Jalankan dengan Docker

```bash
docker compose up -d --build
```

---

## Phase 10 — Tambahan & Penyempurnaan

### 10.1 Favicon & Web Manifest

Siapkan file-file berikut di folder `public/`:

```
public/
├── favicon.ico
├── favicon-16x16.png
├── favicon-32x32.png
├── apple-touch-icon.png
├── android-chrome-192x192.png
├── android-chrome-512x512.png
└── site.webmanifest
```

> Bisa generate di [https://realfavicongenerator.net](https://realfavicongenerator.net)

Tambahkan link favicon di `resources/views/app.blade.php`:

```html
<link rel="icon" type="image/x-icon" href="/favicon.ico" />
```

### 10.2 Logo

Tambahkan logo ke `public/images/`:

```
public/images/
├── logo.png
└── logo-transparent.png
```

Gunakan di `Navbar.jsx` dan `Footer.jsx`.

### 10.3 Cleanup file tidak terpakai

```bash
rm resources/views/welcome.blade.php
rm resources/js/bootstrap.js
```

### 10.4 Verifikasi akhir

```bash
# Build frontend
npm run build

# Jalankan server lokal
php artisan serve

# Jalankan Docker
docker compose up -d --build
```

Akses:

- **Landing page**: `http://localhost:8000`
- **Admin panel**: `http://localhost:8000/admin`

---

## Struktur File Akhir

```
aurify/
├── app/
│   ├── Filament/
│   │   ├── Resources/
│   │   │   ├── ActivityLogResource.php
│   │   │   ├── ActivityLogResource/Pages/ListActivityLogs.php
│   │   │   ├── LeadResource.php
│   │   │   └── LeadResource/Pages/{ListLeads,CreateLead,EditLead}.php
│   │   └── Widgets/
│   │       ├── LeadsChart.php
│   │       └── StatsOverview.php
│   ├── Http/
│   │   ├── Controllers/LeadController.php
│   │   └── Middleware/
│   │       ├── HandleInertiaRequests.php
│   │       ├── SecurityHeaders.php
│   │       └── TrustProxies.php
│   ├── Listeners/AuthActivityLogger.php
│   ├── Models/{Lead,User}.php
│   └── Providers/
│       ├── AppServiceProvider.php
│       ├── EventServiceProvider.php
│       └── Filament/AdminPanelProvider.php
├── bootstrap/
│   ├── app.php
│   └── providers.php
├── database/
│   ├── migrations/
│   └── seeders/DatabaseSeeder.php
├── docker/
│   ├── entrypoint.sh
│   ├── nginx.conf
│   ├── php.ini
│   └── supervisord.conf
├── resources/
│   ├── css/app.css
│   ├── js/
│   │   ├── app.jsx
│   │   ├── Pages/Landing.jsx
│   │   └── Components/{Navbar,Hero,LeadForm,Features,Pricing,Testimonials,CtaFaq,Footer,GoldParticles}.jsx
│   └── views/app.blade.php
├── routes/web.php
├── public/images/
├── .dockerignore
├── .env.example
├── Dockerfile
├── docker-compose.yml
└── vite.config.js
```
