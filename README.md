# Editorial & Photography Blog Website

A dark, minimal, image-led editorial and photography blog built with **Laravel 12**, **Filament 5**, **Vue 3**, **Inertia.js**, **Tailwind CSS 4**, and **MySQL** using **UUID** primary and foreign keys across all domain tables.

---

## Tech Stack

- **Backend**: Laravel 12 (`^12.0`), PHP 8.2+
- **Admin CMS**: Filament 5 Panel Builder (`/admin`)
- **Public Frontend**: Vue 3 + Inertia.js
- **Styling**: Tailwind CSS 4.1+ (`@tailwindcss/vite`) + Vite 6
- **Database**: MySQL 8+ / MariaDB (UUID primary & foreign keys)
- **Media Storage**: Laravel Filesystem (`public` disk via `storage/app/public`)
- **Testing**: PHPUnit 11

---

## Local Setup (Windows / PowerShell)

### 1. Prerequisites
- PHP 8.2+ with extensions: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd`, `intl`, `zip`
- Composer 2.x
- Node.js 20+ & npm
- MySQL 8+ or MariaDB (e.g., via Laragon or XAMPP)

### 2. Install Dependencies
```powershell
composer install
npm install
```

### 3. Configure `.env`
Copy `.env.example` to `.env` if not already present, generate the app key, and configure MySQL:
```powershell
Copy-Item .env.example .env
php artisan key:generate
```

Update your `.env` values:
```env
APP_NAME="NOIR & FRAME"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog_website
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
```

> **Important**: Always point your web server's document root to the `public/` directory (e.g., using `php artisan serve` or a VirtualHost like `http://blog-website.test`), and match `APP_URL` to that URL so `/storage/...` media URLs resolve properly.

### 4. Database Setup & Migrations
Create the `blog_website` database in MySQL, then run migrations:
```powershell
php artisan migrate
```

Optionally seed sample editorial content in **local/development** only (the seeder automatically skips execution when `APP_ENV=production`):
```powershell
# Optional: set initial dev passwords in .env or shell before seeding
php artisan db:seed
```

> Never run `php artisan migrate:fresh` or `php artisan db:wipe` on a database containing user data.

### 5. Link Public Storage
```powershell
php artisan storage:link
```
Uploaded images are stored under `storage/app/public/media/YYYY/MM/`.

### 6. Create the First Admin User
Create an admin account interactively (never hardcode weak passwords):
```powershell
php artisan make:filament-user
```
Panel access at `/admin` is enforced server-side in `App\Models\User::canAccessPanel()` and is restricted to users with `role = 'admin'` or `role = 'editor'`. Ensure the created user has `role` set to `'admin'` (the migration defaults `role` to `'admin'`).

### 7. Run Locally
```powershell
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev
```
- Public site: `http://127.0.0.1:8000`
- Admin CMS: `http://127.0.0.1:8000/admin`

---

## Testing & Build Commands

Tests are configured in `phpunit.xml` to use a separate MySQL database (`blog_website_test`) so development data in `blog_website` is never touched.

```powershell
# Create the test database once
mysql -h 127.0.0.1 -P 3306 -u root -e "CREATE DATABASE IF NOT EXISTS blog_website_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run the full test suite
php artisan test

# Check/format PHP code style with Laravel Pint
php vendor/bin/pint

# Build production frontend assets
npm run build
```

---

## Public Routes & CMS Features

### Public Routes
- `/` — Modular homepage assembled from active `homepage_sections` in `sort_order`
- `/blog` — Paginated article archive with search
- `/blog/{slug}` — Article detail with sanitized HTML, SEO & Open Graph metadata, and related stories
- `/category/{slug}` — Category archive
- `/tag/{slug}` — Tag archive
- `/gallery` — Published photography series archive
- `/gallery/{slug}` — Ordered gallery frames with keyboard-accessible lightbox
- `/about` & `/contact` — Editorial pages + site contact settings
- Custom `404` Inertia page for unmatched public routes

### Admin CMS (`/admin`)
- **Dashboard**: Stats overview (published posts, drafts, categories, media items, galleries)
- **Editorial Content**: Posts, Categories, Tags, Galleries (with drag-and-drop media ordering and captions), Pages
- **Media Library**: Raster-only uploads (JPEG, PNG, WebP up to 5 MB), metadata tracking, and reference-checked safe deletion
- **Admin-Only Settings**: Homepage Sections (active toggle, strict type allowlist, sort order), Site Settings (controlled key allowlist), and Users (`admin` / `editor` roles)

---

## Deployment Notes (PHP Hosting)

Before deploying to a PHP hosting environment, verify that the server meets these requirements:
- **PHP Version & Extensions**: PHP `8.2+` with `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd`, `intl`, and `zip`.
- **Document Root**: Point the domain/virtual host to `public/`, **not** the repository root.
- **Writable Directories**: Ensure `storage/` and `bootstrap/cache/` are writable by the web server user.
- **Environment Variables**: Set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://your-domain.com`, and production database credentials in `.env` (never commit `.env`).
- **Build & Deploy Steps**:
  1. Install production PHP dependencies: `composer install --no-dev --optimize-autoloader`
  2. Build frontend assets (locally or in CI/CD if Node is not available on the host): `npm ci && npm run build`
  3. Run migrations: `php artisan migrate --force`
  4. Link storage (if symlinks are permitted; otherwise configure web server alias for `/storage` -> `storage/app/public`): `php artisan storage:link`
  5. Cache framework & Filament artifacts:
     ```bash
     php artisan optimize
     php artisan filament:optimize
     ```
- **Queues**: `QUEUE_CONNECTION` defaults to `database`. If you configure queued jobs later, run `php artisan queue:work --tries=3` under a process supervisor.
