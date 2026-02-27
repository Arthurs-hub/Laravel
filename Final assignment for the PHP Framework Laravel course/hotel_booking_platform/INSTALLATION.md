# 🚀 Quick Installation Guide

## Prerequisites
- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js & npm

## Installation Steps

### 1. Clone Repository
```bash
git clone https://github.com/Arthurs-hub/Laravel.git
cd Laravel
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hotel_booking_platform
DB_USERNAME=root
DB_PASSWORD=your_password

USE_EXTERNAL_IMAGES=true
GOOGLE_DRIVE_BASE_URL="https://lh3.googleusercontent.com/d/"
```

### 4. Create Database
```sql
CREATE DATABASE hotel_booking_platform;
```

### 5. Setup Database with Sample Data
```bash
php artisan app:quick-setup
```
This command will:
- Run all migrations
- Create 200 hotels with Google Drive images
- Create 1000 rooms
- Add facilities and translations
- Takes ~3 minutes

### 6. Build Assets
```bash
npm run build
```

### 7. Start Application
```bash
php artisan serve
```

Visit: http://localhost:8000

## 📸 Google Drive Images

All 1200+ hotel and room images are automatically loaded from Google Drive:
- **No local storage required**
- **Zero image setup**
- **Works immediately after database setup**

Images use `referrerpolicy="no-referrer"` to bypass Google Drive restrictions.

## 🎯 Quick Commands

Using Makefile:
```bash
make quickstart  # Complete setup
make serve       # Start server
make test        # Run tests
make clean       # Clear caches
```

## 👤 First User Setup

1. Register at: http://localhost:8000/register
2. Promote to admin:
```sql
UPDATE users SET role = 'admin' WHERE email = 'your-email@example.com';
```

## 📚 Full Documentation

See [README.md](README.md) for complete documentation including:
- Complete API documentation (48 endpoints)
- Multi-language support (7 languages)
- Security features
- Testing guide
- Troubleshooting

## 🐛 Common Issues

### Images not loading?
- Check `.env`: `USE_EXTERNAL_IMAGES=true`
- Check `.env`: `GOOGLE_DRIVE_BASE_URL="https://lh3.googleusercontent.com/d/"`
- Clear cache: `php artisan config:clear`
- Hard refresh browser: Ctrl+Shift+R

### Database errors?
- Verify database exists
- Check `.env` credentials
- Run: `php artisan migrate:fresh`

## 🔗 Links

- **Repository**: https://github.com/Arthurs-hub/Laravel
- **Main Site**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin
- **Manager Panel**: http://localhost:8000/manager
