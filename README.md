<h1 align="center">🎭 MemeVault</h1>

<p align="center">
  <strong>The ultimate meme template platform — browse, customize, and download memes with ease.</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Filament-3.x-F59E0B?style=for-the-badge" alt="Filament">
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-38BDF8?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="MIT License">
</p>

---

## 📖 About MemeVault

**MemeVault** is a full-featured web application built with **Laravel** that allows users to browse a large library of meme templates, customize them with a built-in canvas editor, and download them directly. The platform supports tiered subscriptions (Free, Premium, Business) giving users different levels of access to templates, download quality, and support.

An integrated **Filament Admin Panel** gives administrators full control over templates, categories, tags, and users.

---

## ✨ Features

### 🖼️ Template Library
- Browse **1,000+ meme templates** organized by category and tags
- Filter templates by **category**, **tag**, or **search keyword**
- Sort by **latest**, **most popular**, **trending**, or **name**
- Featured templates highlighted on the homepage
- Random template picker for spontaneous fun
- Template detail page with related meme suggestions
- View count and download count tracking per template

### ✏️ Meme Editor
- Built-in **canvas-based meme editor** for each template
- Add and customize **text overlays** on meme images
- Image proxy system to safely load external images into the canvas
- Local image caching for faster editing performance
- Save and download your customized meme directly from the editor

### 💳 Subscription / Pricing Plans

| Plan | Price | Key Benefits |
|------|-------|--------------|
| **Free** | $0/mo | 1,000+ templates, basic editor, download with watermark, community support |
| **Premium** | $9.99/mo or $99.99/yr | No watermark, premium templates, HD/4K downloads, priority support (24h), ad-free, advanced editor tools |
| **Business** | $29.99/mo or $299.99/yr | Everything in Premium + white-label branding, custom watermark, team collaboration (5 users), API access, bulk downloads, analytics dashboard, dedicated account manager |

- Monthly and yearly billing options (save 17% yearly)
- Subscription management from the user dashboard
- Cancel subscription at any time

### 👤 User Authentication & Profiles
- Secure registration and login via **Laravel Breeze**
- Email verification support
- User avatar and profile management
- Account activation/deactivation system

### 📥 Download System
- One-click template download
- **Watermark-free downloads** for Premium and Business users
- Free users receive downloads with a MemeVault watermark
- Download history tracked per user
- IP address logging for download analytics

### 🎫 Support Ticket System
- Submit support tickets without registration (public form)
- Authenticated users can view and reply to their tickets
- Priority support for Premium/Business subscribers
- Admin can manage, respond to, and close all tickets

### 📊 User Dashboard
- Overview of subscription status and expiry date
- Personal download history
- Subscription upgrade/downgrade options
- Active support tickets at a glance

### 🛡️ Admin Panel (Filament)
Accessible at `/admin`, the admin panel provides:

- **Templates Management** — Create, edit, delete, and toggle templates; set premium tier; manage text overlay areas; upload images
- **Categories Management** — Full CRUD for meme categories with slug auto-generation
- **Tags Management** — Create and assign tags to templates
- **Admin User Management** — Manage administrator accounts and roles
- **Settings** — Global site settings via a key-value store

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 12.x (PHP 8.2+) |
| Admin Panel | Filament 3.x |
| Frontend | Blade Templates, Tailwind CSS 3.x, Alpine.js |
| Build Tool | Vite |
| Authentication | Laravel Breeze |
| Real-time | Livewire 3.x |
| Database | MySQL |
| File Storage | Laravel Storage (local / S3-compatible) |
| Dev Server | Laragon |

---

## 🚀 Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm
- MySQL
- Laragon (or any local server)

### Installation

```bash
# Clone the repository
git clone https://github.com/your-username/meme-vault.git
cd meme-vault

# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Copy the environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env, then run migrations
php artisan migrate

# Link storage
php artisan storage:link

# Build frontend assets
npm run dev
```

### Creating an Admin User

```bash
php create-admin.php
```

Or use the Artisan tinker to create a Filament admin manually.

### Running the App

Start your Laragon server (or use `php artisan serve`) and navigate to:

- **Website:** `http://localhost/meme-vault/public`
- **Admin Panel:** `http://localhost/meme-vault/public/admin`

---

## 📁 Project Structure

```
meme-vault/
├── app/
│   ├── Filament/           # Admin panel resources (Templates, Categories, Tags, Admins)
│   ├── Http/Controllers/   # Web controllers (Home, Templates, Editor, Pricing, Support, Dashboard)
│   ├── Livewire/           # Livewire components
│   ├── Models/             # Eloquent models (User, Template, Category, Tag, Subscription, SupportTicket, ...)
│   └── Providers/
├── database/
│   ├── migrations/         # Database schema migrations
│   └── seeders/            # Database seeders
├── resources/
│   ├── views/              # Blade templates (home, templates, editor, pricing, support, dashboard, auth)
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php             # Web routes
│   ├── auth.php            # Auth routes (Breeze)
│   └── api.php             # API routes
└── public/                 # Public assets
```

---

## 🔐 User Roles & Permissions

| Feature | Free | Premium | Business |
|---------|------|---------|----------|
| Browse templates | ✅ | ✅ | ✅ |
| Use meme editor | ✅ | ✅ | ✅ |
| Download with watermark | ✅ | ❌ | ❌ |
| Download without watermark | ❌ | ✅ | ✅ |
| Access premium templates | ❌ | ✅ | ✅ |
| Access business templates | ❌ | ❌ | ✅ |
| Priority support | ❌ | ✅ (24h) | ✅ (4h) |
| HD / 4K downloads | ❌ | ✅ | ✅ |
| API access | ❌ | ❌ | ✅ |
| Analytics dashboard | ❌ | ❌ | ✅ |
| White-label / custom watermark | ❌ | ❌ | ✅ |
| Team collaboration | ❌ | ❌ | ✅ (5 users) |

---

## 📄 License

MemeVault is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
