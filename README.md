# Aurify — Financial Management Landing Page

```URL
https://aurify.miftech.web.id
```


A premium SaaS financial management landing page with admin dashboard, built with Laravel, React, Inertia.js, and Tailwind CSS. Features a gold-themed design, lead capture form, and a full-featured admin panel powered by Filament.

## Table of Contents

- [Tech Stack](#tech-stack)
- [Features](#features)
- [Project Structure](#project-structure)
- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
  - [Database Setup](#database-setup)
  - [Running the Application](#running-the-application)
- [Docker Deployment](#docker-deployment)
- [Environment Variables](#environment-variables)
- [Database Schema](#database-schema)
- [Security](#security)

## Tech Stack

### Backend

| Package                      | Description                          |
| ---------------------------- | ------------------------------------ |
| **Laravel 12**               | PHP web framework                    |
| **Inertia.js v2**            | SPA bridge between Laravel and React |
| **Filament v3.3**            | Admin panel and dashboard builder    |
| **Spatie Activity Log v4.9** | Model activity logging               |

### Frontend

| Package                 | Description                           |
| ----------------------- | ------------------------------------- |
| **React 19**            | UI component library                  |
| **Tailwind CSS v4**     | Utility-first CSS framework           |
| **Vite 7**              | Frontend build tool                   |
| **@inertiajs/react**    | React adapter for Inertia.js          |
| **react-scroll**        | Smooth scrolling for navigation links |

### Infrastructure

| Tool               | Description                        |
| ------------------ | ---------------------------------- |
| **Docker**         | Containerized deployment           |
| **Nginx**          | Web server / reverse proxy         |
| **Supervisord**    | Process manager (PHP-FPM + Nginx)  |
| **MySQL 8.0**      | Relational database                |

## Features

| Feature                    | Description                                                                   |
| -------------------------- | ----------------------------------------------------------------------------- |
| **Landing Page**           | Premium gold-themed landing page with animated particles, hero, and CTA       |
| **Lead Capture Form**      | 4-field form (name, WhatsApp, email, organization) with validation            |
| **Admin Dashboard**        | Filament-powered dashboard with stats overview and leads chart                |
| **Leads CRUD**             | Full create, read, update, delete for leads with search and filters           |
| **Activity Logging**       | Tracks all CRUD operations and authentication events (login/logout)           |
| **Rate Limiting**          | 5 requests per minute on lead submission endpoint                             |
| **Security Headers**       | X-Content-Type-Options, X-Frame-Options, X-XSS-Protection, Referrer-Policy   |
| **Input Sanitization**     | All inputs sanitized with `strip_tags` and `trim` before storage              |
| **Responsive Design**      | Mobile-friendly layout across all screen sizes                                |
| **Docker Ready**           | Multi-stage Docker build for production deployment                            |

## Project Structure

```
app/
├── Filament/
│   ├── Resources/
│   │   ├── LeadResource.php              # Lead CRUD resource
│   │   ├── LeadResource/Pages/           # List, Create, Edit pages
│   │   ├── ActivityLogResource.php       # Read-only activity log viewer
│   │   └── ActivityLogResource/Pages/    # List page
│   └── Widgets/
│       ├── StatsOverview.php             # Dashboard stats (leads count, etc.)
│       └── LeadsChart.php                # 30-day leads line chart
├── Http/
│   ├── Controllers/
│   │   └── LeadController.php            # Handles lead form submission
│   └── Middleware/
│       ├── HandleInertiaRequests.php      # Shares flash data to frontend
│       └── SecurityHeaders.php           # Adds security response headers
├── Listeners/
│   └── AuthActivityLogger.php            # Logs login/logout events
├── Models/
│   ├── Lead.php                          # Lead model with activity logging
│   └── User.php                          # User model with Filament access
└── Providers/
    ├── AppServiceProvider.php            # Rate limiter configuration
    ├── EventServiceProvider.php          # Auth event listeners
    └── Filament/
        └── AdminPanelProvider.php        # Filament panel config (gold theme)

resources/
├── css/
│   └── app.css                           # Tailwind config with gold theme
├── js/
│   ├── app.jsx                           # Inertia app entry point
│   ├── Pages/
│   │   └── Landing.jsx                   # Main landing page
│   └── Components/
│       ├── Navbar.jsx                    # Fixed navigation with mobile menu
│       ├── Hero.jsx                      # Hero section with dashboard mockup
│       ├── LeadForm.jsx                  # Lead capture form
│       ├── Features.jsx                  # Feature cards with scroll animation
│       ├── Pricing.jsx                   # 3-tier pricing section
│       ├── Testimonials.jsx              # User testimonials
│       ├── CtaFaq.jsx                    # CTA banner and FAQ accordion
│       ├── Footer.jsx                    # Footer with links and contacts
│       └── GoldParticles.jsx             # Canvas gold particle animation
└── views/
    └── app.blade.php                     # Inertia root template

routes/
└── web.php                               # GET / (landing) + POST /leads

database/
├── migrations/                           # Users, leads, activity_log tables
└── seeders/
    └── DatabaseSeeder.php                # Seeds admin users

docker/
├── entrypoint.sh                         # Auto migration, caching, seeding
├── nginx.conf                            # Nginx config (port 8000)
├── php.ini                               # PHP production settings
└── supervisord.conf                      # Process manager config
```

### Layer Description

| Layer           | Folder               | Description                                              |
| --------------- | -------------------- | -------------------------------------------------------- |
| **Routes**      | `routes/`            | Define web endpoints and apply middlewares                |
| **Controllers** | `Http/Controllers/`  | Handle HTTP requests, validate input, return responses    |
| **Models**      | `Models/`            | Eloquent models with activity logging traits              |
| **Middleware**  | `Http/Middleware/`   | Security headers, Inertia data sharing                   |
| **Filament**    | `Filament/`          | Admin panel resources, pages, and widgets                |
| **Components**  | `js/Components/`     | React UI components for the landing page                 |
| **Providers**   | `Providers/`         | Service providers for rate limiting, events, admin panel  |

## Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0
- npm

### Installation

1. Clone the repository

```bash
git clone https://github.com/MuhammadMiftaa/refina-landing-page.git
cd refina-landing-page
```

2. Install PHP dependencies

```bash
composer install
```

3. Install Node.js dependencies

```bash
npm install
```

4. Create `.env` file

```bash
cp .env.example .env
```

5. Generate application key

```bash
php artisan key:generate
```

### Database Setup

1. Create a MySQL database

```sql
CREATE DATABASE refina;
```

2. Update your `.env` file with database credentials

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=refina
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

3. Run migrations

```bash
php artisan migrate
```

4. Seed admin users

```bash
php artisan db:seed
```

**Default admin accounts after seeding:**

| Email            | Password   | Role  |
| ---------------- | ---------- | ----- |
| admin1@wafa.id   | admin123   | Admin |
| admin2@wafa.id   | admin123   | Admin |

### Running the Application

**Development mode** (run both in separate terminals):

```bash
# Terminal 1 — Laravel server
php artisan serve

# Terminal 2 — Vite dev server
npm run dev
```

**Production build:**

```bash
npm run build
php artisan serve
```

The application will be available at `http://localhost:8000`.  
The admin panel is at `http://localhost:8000/admin`.

## Docker Deployment

Build and run with Docker Compose:

```bash
docker compose up -d --build
```

This will start:

- **app** — Laravel + Nginx + PHP-FPM on port `8000`
- **mysql** — MySQL 8.0 on port `3306`

The entrypoint script automatically handles:

- Application key generation
- Config, route, and view caching
- Database migrations
- Database seeding (first run only)

To stop the containers:

```bash
docker compose down
```

To stop and remove all data (including database):

```bash
docker compose down -v
```

## Environment Variables

| Variable        | Description                    | Default                 |
| --------------- | ------------------------------ | ----------------------- |
| `APP_NAME`      | Application name               | `Aurify`                |
| `APP_ENV`       | Environment (local/production) | `local`                 |
| `APP_KEY`       | Application encryption key     | *(auto-generated)*      |
| `APP_DEBUG`     | Debug mode                     | `true`                  |
| `APP_URL`       | Application URL                | `http://localhost:8000`  |
| `DB_CONNECTION` | Database driver                | `mysql`                 |
| `DB_HOST`       | Database host                  | `127.0.0.1`             |
| `DB_PORT`       | Database port                  | `3306`                  |
| `DB_DATABASE`   | Database name                  | `refina`                |
| `DB_USERNAME`   | Database username              | `user`                  |
| `DB_PASSWORD`   | Database password              | `123`                   |
| `BCRYPT_ROUNDS` | Password hashing rounds        | `12`                    |

## Database Schema

### `leads`

| Column       | Type         | Description                |
| ------------ | ------------ | -------------------------- |
| `id`         | BIGINT (PK)  | Auto-increment ID          |
| `nama`       | VARCHAR      | Full name (required)       |
| `whatsapp`   | VARCHAR      | WhatsApp number (required) |
| `email`      | VARCHAR      | Email address (required)   |
| `lembaga`    | VARCHAR      | Organization (optional)    |
| `created_at` | TIMESTAMP    | Creation timestamp         |
| `updated_at` | TIMESTAMP    | Last update timestamp      |

### `activity_log`

| Column         | Type        | Description                          |
| -------------- | ----------- | ------------------------------------ |
| `id`           | BIGINT (PK) | Auto-increment ID                    |
| `log_name`     | VARCHAR     | Log channel (default/auth)           |
| `description`  | VARCHAR     | Event description                    |
| `subject_type` | VARCHAR     | Related model class                  |
| `subject_id`   | BIGINT      | Related model ID                     |
| `causer_type`  | VARCHAR     | User who caused the action           |
| `causer_id`    | BIGINT      | User ID who caused the action        |
| `event`        | VARCHAR     | Event type (created/updated/deleted) |
| `properties`   | JSON        | Changed attributes (old & new)       |
| `batch_uuid`   | UUID        | Batch identifier                     |
| `created_at`   | TIMESTAMP   | Event timestamp                      |

## Security

| Measure                | Implementation                                                                                  |
| ---------------------- | ----------------------------------------------------------------------------------------------- |
| **CSRF Protection**    | Laravel built-in CSRF token verification on all POST requests                                   |
| **Rate Limiting**      | 5 requests per minute per IP on `/leads` endpoint                                               |
| **Security Headers**   | X-Content-Type-Options, X-Frame-Options, X-XSS-Protection, Referrer-Policy, Permissions-Policy  |
| **Input Sanitization** | All user inputs sanitized with `strip_tags()` and `trim()`                                      |
| **Password Hashing**   | Bcrypt with 12 rounds                                                                           |
| **Authentication**     | Filament session-based authentication for admin panel                                           |
| **OPcache**            | Enabled in production Docker build                                                              |
| **PHP Hardening**      | `expose_php = Off` in production                                                                |
| **Nginx Hardening**    | `server_tokens off`, hidden `X-Powered-By`, denied dotfile access                               |
