# 🏨 Hotel Booking Platform

A comprehensive, enterprise-grade hotel booking platform built with Laravel 12, featuring complete multi-language support, advanced booking management, real-time communication, and modern UI/UX design.

## 🚀 Complete Setup from Scratch

### Prerequisites
- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js & npm

## 📸 Image System - Google Drive Integration

### Automatic Image Display
The platform uses **Google Drive** to serve all 1200+ hotel and room images automatically. No local image folder is required.

### Image Configuration
- **Hotel Images**: 200 hotels × 1 image each = 200 images
- **Room Images**: 200 hotels × 5 rooms each = 1000 images
- **Total Images**: 1200 images served from Google Drive
- **No Local Storage**: Images are loaded directly from Google Drive URLs
- **Zero Setup**: Works immediately after database setup

### Environment Configuration
Ensure these settings in your `.env` file:
```env
USE_EXTERNAL_IMAGES=true
GOOGLE_DRIVE_BASE_URL="https://lh3.googleusercontent.com/d/"
```

### Google Drive URL Formats
The platform supports multiple Google Drive URL formats:
- **GoogleUserContent** (Recommended): `https://lh3.googleusercontent.com/d/{FILE_ID}`
- **Standard Drive**: `https://drive.google.com/uc?export=view&id={FILE_ID}`
- **Thumbnail**: `https://drive.google.com/thumbnail?id={FILE_ID}`

**Note**: GoogleUserContent format (`lh3.googleusercontent.com`) works best for public images.

### How It Works
1. **Image Helper**: `app/Helpers/ImageHelper.php` generates Google Drive URLs
2. **Configuration**: `config/images.php` contains all Google Drive file IDs
3. **Automatic Fallback**: Uses Unsplash images if Google Drive fails
4. **No Downloads**: Images stream directly from Google Drive

### Image URL Format
```
https://lh3.googleusercontent.com/d/{GOOGLE_DRIVE_FILE_ID}
```

### Supported Countries & Hotels
- **20 Countries**: Australia, Brazil, Canada, China, France, Germany, India, Italy, Japan, Mexico, Norway, Russia, Singapore, South Korea, Spain, Thailand, Turkey, UAE, United Kingdom, USA
- **200 Hotels**: 10 hotels per country
- **1000 Room Images**: 5 room images per hotel
- **All Images**: Automatically configured and ready to use

### Step 1: Create Database
```sql
-- Connect to MySQL and create database
CREATE DATABASE hotel_booking_platform;
```

### Step 2: Import Database Structure
```bash
# Import the complete database schema with all tables and basic data
mysql -u root -p hotel_booking_platform < database.sql
```

### Step 3: Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### Step 4: Configure Environment
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database connection in .env file:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=hotel_booking_platform
# DB_USERNAME=root
# DB_PASSWORD=your_password
```

### Step 5: Populate Database with Sample Data
```bash
# Fill database with hotels, rooms, facilities and Arabic translations (fulfillment takes up to 3 minutes)
php artisan app:quick-setup 
```

### Step 6: Start Application
```bash
# Start Laravel development server
php artisan serve

# In separate terminal, compile assets
npm run build
```

### Step 7: Access Application
- **Main Site**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin (register first user, then promote to admin)
- **Manager Panel**: http://localhost:8000/manager (create manager account through admin panel)

## 🚀 Quick Setup (Alternative Method)

### One-Command Database Setup (if database already exists)
```bash
# Complete database setup with hotels, rooms, and facilities only
php artisan app:quick-setup

# Alternative: Manual setup with options
php artisan app:setup-database --fresh
```

### Start Development Server
```bash
# Start Laravel server
php artisan serve

# In separate terminal for asset watching
npm run build
```

## 🌟 Key Features

- **Multi-Language Support**: 7 languages (English, Russian, Arabic, French, German, Italian, Spanish)
- **Role-Based Access**: Admin, Manager, and User roles with specific permissions
- **Real-time Booking System**: Live availability checking with conflict prevention
- **Review & Rating System**: Guest reviews with moderation and approval workflow
- **Two-Factor Authentication**: Enhanced security with 2FA support
- **Responsive Design**: Mobile-first design optimized for all devices
- **Email Notifications**: Automated booking confirmations and reminders
- **RESTful API**: Complete JSON API for external integrations and testing

## 👥 User Registration

After running `php artisan app:quick-setup`:

### No Default Users Created
- **Users Table**: Completely empty by design
- **Registration**: Users must register through the application interface
- **First User**: The first registered user can be promoted to admin manually in the database
- **Admin Access**: Update the `role` field to `admin` in the users table for administrative access

### User Roles Available
- **admin**: Full system administration, user management, hotel management
- **manager**: Hotel-specific management, booking oversight for assigned hotels  
- **user**: Booking creation, profile management, reviews (default role)

## 🛣️ Complete API Documentation

### Base URL
```
http://localhost:8000/api
```

### 🔐 Authentication API (Local Development Only)

**⚠️ IMPORTANT**: These endpoints are **ONLY available in local development** environment (`APP_ENV=local`). They are automatically disabled in production.

#### 1. Register User WITHOUT 2FA
```http
POST /api/register
```
**Environment**: Local development only  
**Content-Type**: `application/json`
**Headers**: `Accept: application/json`

**Request Body**:
```json
{
    "full_name": "John Doe",
    "email": "john.doe@example.com",
    "phone": "+1234567890",
    "date_of_birth": "1990-01-01",
    "gender": "male",
    "country": "USA",
    "city": "New York",
    "address": "123 Main St",
    "postal_code": "10001",
    "passport_number": "AB123456",
    "password": "password123",
    "password_confirmation": "password123",
    "language": "en",
    "two_factor_enabled": 0
}
```

**Response (Success 201)**:
```json
{
    "success": true,
    "message": "Registration successful",
    "user": {
        "id": 1,
        "full_name": "John Doe",
        "email": "john.doe@example.com",
        "role": "user"
    },
    "redirect": "/dashboard"
}
```

#### 2. Login User WITHOUT 2FA
```http
POST /api/login
```
**Headers**: `Accept: application/json`
**Content-Type**: `application/json`

**Request Body**:
```json
{
    "email": "john.doe@example.com",
    "password": "password123",
    "remember": true
}
```

**Response (Success 200)**:
```json
{
    "success": true,
    "message": "Login successful",
    "user": {
        "id": 1,
        "full_name": "John Doe",
        "email": "john.doe@example.com",
        "role": "user"
    },
    "redirect": "/hotels"
}
```

#### 3. Register User WITH 2FA
```http
POST /api/register
```
**Headers**: `Accept: application/json`
**Content-Type**: `application/json`

**Request Body**:
```json
{
    "full_name": "Jane Smith",
    "email": "jane.smith@example.com",
    "phone": "+1234567891",
    "date_of_birth": "1990-01-01",
    "gender": "female",
    "country": "USA",
    "city": "New York",
    "address": "456 Oak St",
    "postal_code": "10002",
    "passport_number": "CD789012",
    "password": "password123",
    "password_confirmation": "password123",
    "language": "en",
    "two_factor_enabled": 1
}
```

**Response (Success 201)**:
```json
{
    "success": true,
    "message": "Registration successful",
    "user": {
        "id": 2,
        "full_name": "Jane Smith",
        "email": "jane.smith@example.com",
        "role": "user"
    },
    "redirect": "/two-factor/method"
}
```

#### 4A. Select 2FA Method - Email
```http
POST /api/two-factor/method
```
**Headers**: `Accept: application/json`
**Content-Type**: `application/x-www-form-urlencoded`

**Body (form-data)**:
```
method=email
```

**Response (Success 200)**:
```json
{
    "success": true,
    "message": "Email 2FA method selected",
    "redirect": "/api/two-factor/verify"
}
```

#### 4B. Select 2FA Method - Google Authenticator
```http
POST /api/two-factor/method
```
**Headers**: `Accept: application/json`
**Content-Type**: `application/x-www-form-urlencoded`

**Body (form-data)**:
```
method=google_authenticator
```

**Response (Success 200)**:
```json
{
    "success": true,
    "message": "Google Authenticator setup required",
    "setup_data": {
        "manual_entry": {
            "account_name": "jane.smith@example.com",
            "secret_key": "GENERATED_SECRET_KEY",
            "issuer": "Hotel Booking Platform"
        }
    },
    "redirect": "/api/two-factor/verify"
}
```

#### 5. Validate 2FA - Email Code
```http
POST /api/two-factor/verify
```
**Headers**: `Accept: application/json`
**Content-Type**: `application/x-www-form-urlencoded`

**Body (form-data)**:
```
code=123456
```
*Get code from `storage/logs/laravel.log or check your email`*

**Response (Success 200)**:
```json
{
    "success": true,
    "message": "Two-factor authentication completed",
    "user": {
        "id": 2,
        "full_name": "Jane Smith",
        "email": "jane.smith@example.com",
        "role": "user"
    },
    "redirect": "/api/hotels"
}
```

#### 6. Validate 2FA - Google Authenticator Code
```http
POST /api/two-factor/verify
```
**Headers**: `Accept: application/json`
**Content-Type**: `application/x-www-form-urlencoded`

**Body (form-data)**:
```
code=654321
```
*Get code from Google Authenticator app*

**Response**: Same as Email 2FA validation

#### 7. Login User WITH 2FA
```http
POST /api/login
```
**Headers**: `Accept: application/json`
**Content-Type**: `application/json`

**Request Body**:
```json
{
    "email": "jane.smith@example.com",
    "password": "password123"
}
```

**Response (Success 200)**:
```json
{
    "success": true,
    "message": "Two-factor authentication required",
    "two_factor_required": true,
    "redirect": "/api/two-factor/verify"
}
```

*Then use step 5 or 6 to complete 2FA verification*

#### Logout User
```http
POST /api/logout
```
**Headers**: `Accept: application/json`

**Response (Success 200)**:
```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

### 🏨 Hotels API (Public Access)

#### 8. Get All Hotels (No Authentication Required)

```http
GET /api/hotels
```
**Authentication**: Not required  
**Headers**: `Accept: application/json`

**Basic Request**:
```http
GET /api/hotels
```

**Response (Success 200)**:
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "The Ritz-Carlton Toronto",
            "description": "Elegant hotel in financial district.",
            "address": "181 Wellington Street West, Toronto, ON",
            "country": "Canada",
            "city": "Toronto",
            "rating": 4.8,
            "manager_id": 2,
            "facilities": [
                {
                    "id": 1,
                    "title": "Wi-Fi",
                    "icon": "wifi"
                }
            ]
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 10,
        "per_page": 15,
        "total": 150
    }
}
```

#### 9. Filter Hotels by Country
```http
GET /api/hotels?country=USA
```
**Headers**: `Accept: application/json`

#### 10. Filter Hotels by City
```http
GET /api/hotels?city=New York
```
**Headers**: `Accept: application/json`

#### 11. Search Hotels by Text
```http
GET /api/hotels?search=luxury
```
**Headers**: `Accept: application/json`

#### 12. Filter Hotels by Rating
```http
GET /api/hotels?rating=4
```
**Headers**: `Accept: application/json`

#### 13. Filter Hotels by Price Range
```http
GET /api/hotels?price_from=100&price_to=500
```
**Headers**: `Accept: application/json`

#### 14. Filter Hotels by Facilities
```http
GET /api/hotels?facilities[]=1&facilities[]=3
```
**Headers**: `Accept: application/json`
*Filter by Wi-Fi (ID: 1) and Pool (ID: 3)*

#### 15. Sort Hotels
```http
GET /api/hotels?sort=price_asc
```
**Headers**: `Accept: application/json`
**Sort Options**: `price_asc`, `price_desc`, `rating_desc`, `name_asc`, `default`

#### 16. Paginate Hotels
```http
GET /api/hotels?per_page=10&page=2
```
**Headers**: `Accept: application/json`

#### 17. Combined Filters
```http
GET /api/hotels?country=USA&rating=4&sort=price_desc&per_page=5
```
**Headers**: `Accept: application/json`

#### 18. Get Available Countries
```http
GET /api/countries
```
**Headers**: `Accept: application/json`

**Response (Success 200)**:
```json
{
    "success": true,
    "data": [
        "Australia",
        "Brazil",
        "Canada",
        "USA"
    ]
}
```

#### 19. Get All Facilities
```http
GET /api/facilities
```
**Headers**: `Accept: application/json`

**Response (Success 200)**:
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Wi-Fi",
            "icon": "wifi"
        },
        {
            "id": 2,
            "title": "Parking",
            "icon": "parking"
        }
    ]
}
```

#### 20. Get Hotel Details
```http
GET /api/hotels/1
```
**Headers**: `Accept: application/json`

**Response (Success 200)**:
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "The Ritz-Carlton Toronto",
        "description": "Elegant hotel in financial district.",
        "address": "181 Wellington Street West, Toronto, ON",
        "country": "Canada",
        "city": "Toronto",
        "rating": 4.8,
        "rooms": [
            {
                "id": 1,
                "title": "Deluxe Room",
                "type": "deluxe",
                "price": 150.00,
                "floor_area": 35
            }
        ],
        "facilities": [
            {
                "id": 1,
                "title": "Wi-Fi",
                "icon": "wifi"
            }
        ]
    }
}
```

### 🛏️ Room Availability API

#### 21. Check Room Availability
```http
POST /api/rooms/check-availability
```
**Authentication**: Not required  
**Headers**: `Accept: application/json`, `Content-Type: application/json`

**Request Body**:
```json
{
    "room_id": 1,
    "started_at": "2025-12-01",
    "finished_at": "2025-12-05"
}
```

**Response (Available)**:
```json
{
    "available": true,
    "days": 4,
    "price_per_night": 150.00,
    "total_price": 600.00
}
```

**Response (Not Available)**:
```json
{
    "available": false,
    "days": 4,
    "price_per_night": 150.00,
    "total_price": 600.00
}
```

### 👤 Authenticated User API

**⚠️ Authentication Required**: All endpoints below require user to be logged in

#### 22. Get User Profile
```http
GET /api/profile
```
**Authentication**: Required (session-based)  
**Headers**: `Accept: application/json`

**Response (Success 200)**:
```json
{
    "success": true,
    "data": {
        "id": 1,
        "full_name": "John Doe",
        "email": "john.doe@example.com",
        "phone": "+1234567890",
        "role": "user",
        "country": "USA",
        "city": "New York",
        "two_factor_enabled": false
    }
}
```

#### 23. Create Booking
```http
POST /api/bookings
```
**Authentication**: Required (session-based)  
**Headers**: `Accept: application/json`, `Content-Type: application/json`

**Request Body**:
```json
{
    "room_id": 1,
    "started_at": "2025-12-01",
    "finished_at": "2025-12-05",
    "special_requests": "Late check-in please"
}
```

**Response (Success 201)**:
```json
{
    "success": true,
    "message": "Booking created successfully",
    "data": {
        "id": 1,
        "room_id": 1,
        "started_at": "2025-12-01",
        "finished_at": "2025-12-05",
        "total_price": 600.00,
        "status": "confirmed",
        "special_requests": "Late check-in please"
    }
}
```

#### 24. Get User Bookings
```http
GET /api/bookings
```
**Authentication**: Required (session-based)  
**Headers**: `Accept: application/json`

**Response (Success 200)**:
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "room_id": 1,
            "started_at": "2025-12-01",
            "finished_at": "2025-12-05",
            "total_price": 600.00,
            "status": "confirmed",
            "special_requests": "Late check-in",
            "room": {
                "id": 1,
                "title": "Deluxe Room",
                "hotel": {
                    "id": 1,
                    "title": "The Ritz-Carlton Toronto"
                }
            }
        }
    ]
}
```

#### 25. Update Booking
```http
PUT /api/bookings/1
```
**Authentication**: Required (session-based)  
**Headers**: `Accept: application/json`, `Content-Type: application/json`

**Request Body**:
```json
{
    "started_at": "2025-12-10",
    "finished_at": "2025-12-15",
    "special_requests": "Updated requests"
}
```

**Response (Success 200)**:
```json
{
    "success": true,
    "message": "Booking updated successfully"
}
```

#### 26. Cancel Booking
```http
DELETE /api/bookings/1
```
**Authentication**: Required (session-based)  
**Headers**: `Accept: application/json`

**Response (Success 200)**:
```json
{
    "success": true,
    "message": "Booking cancelled successfully"
}
```

**Note**: Booking status changes to "cancelled" but remains in database for audit purposes.

### 👑 Admin API

**⚠️ Admin Role Required**: All endpoints below require user role to be 'admin'

#### How to Become Admin
1. Register a user via API (steps 1-2 above)
2. Update user role in database:
```sql
UPDATE users SET role = 'admin' WHERE email = 'your-email@example.com';
```
3. Login again to get admin permissions

#### 27. Get All Users
```http
GET /api/admin/users
```
**Authentication**: Required (admin role)  
**Headers**: `Accept: application/json`

**Response (Success 200)**:
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "full_name": "John Doe",
            "email": "john@example.com",
            "role": "user",
            "created_at": "2025-01-15T10:30:00.000000Z"
        }
    ]
}
```

#### 28. Get All Bookings (Admin)
```http
GET /api/admin/bookings
```
**Authentication**: Required (admin role)  
**Headers**: `Accept: application/json`

**Optional Filters**:
- `?status=confirmed` - Filter by status
- `?hotel_id=1` - Filter by hotel
- `?user_id=1` - Filter by user

**Response (Success 200)**:
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "user_id": 1,
            "room_id": 1,
            "status": "confirmed",
            "total_price": 600.00,
            "user": {
                "id": 1,
                "full_name": "John Doe",
                "email": "john@example.com"
            },
            "room": {
                "id": 1,
                "title": "Deluxe Room",
                "hotel": {
                    "id": 1,
                    "title": "Hotel Name"
                }
            }
        }
    ]
}
```

#### 29. Create Hotel
```http
POST /api/admin/hotels
```
**Authentication**: Required (admin role)  
**Headers**: `Accept: application/json`, `Content-Type: application/json`

**Request Body**:
```json
{
    "title": "New Test Hotel",
    "description": "Hotel description",
    "address": "Hotel address",
    "country": "USA",
    "city": "New York",
    "rating": 4.5,
    "manager_id": null
}
```

**Response (Success 201)**:
```json
{
    "success": true,
    "message": "Hotel created successfully",
    "data": {
        "id": 201,
        "title": "New Test Hotel",
        "country": "USA",
        "city": "New York",
        "rating": 4.5
    }
}
```

#### 30. Update Hotel
```http
PUT /api/admin/hotels/201
```
**Authentication**: Required (admin role)  
**Headers**: `Accept: application/json`, `Content-Type: application/json`

**Request Body**:
```json
{
    "title": "Updated Hotel Name",
    "description": "Updated description",
    "rating": 4.8
}
```

#### 31. Delete Hotel
```http
DELETE /api/admin/hotels/201
```
**Authentication**: Required (admin role)  
**Headers**: `Accept: application/json`

**Response (Success 200)**:
```json
{
    "success": true,
    "message": "Hotel deleted successfully"
}
```

#### 32. Create Room
```http
POST /api/admin/rooms
```
**Authentication**: Required (admin role)  
**Headers**: `Accept: application/json`, `Content-Type: application/json`

**Request Body**:
```json
{
    "hotel_id": 1,
    "title": "Test Room",
    "description": "Room description",
    "type": "deluxe",
    "price": 150.00,
    "floor_area": 35
}
```

#### 33. Update Room
```http
PUT /api/admin/rooms/1
```
**Authentication**: Required (admin role)  
**Headers**: `Accept: application/json`, `Content-Type: application/json`

**Request Body**:
```json
{
    "title": "Updated Room",
    "price": 200.00,
    "floor_area": 40
}
```

#### 34. Delete Room
```http
DELETE /api/admin/rooms/1
```
**Authentication**: Required (admin role)  
**Headers**: `Accept: application/json`

#### 35. System Statistics
```http
GET /api/admin/stats
```
**Authentication**: Required (admin role)  
**Headers**: `Accept: application/json`

**Response (Success 200)**:
```json
{
    "success": true,
    "data": {
        "total_users": 150,
        "total_hotels": 200,
        "total_rooms": 1000,
        "total_bookings": 500,
        "revenue": 125000.00
    }
}
```

### 🏨 Manager API

**⚠️ Manager Role Required**: All endpoints below require user role to be 'manager'

#### How to Become Manager
1. Register a user via API (steps 1-2 above)
2. Update user role in database:
```sql
UPDATE users SET role = 'manager' WHERE email = 'manager@example.com';
```
3. Assign hotel to manager:
```sql
UPDATE hotels SET manager_id = (SELECT id FROM users WHERE email = 'manager@example.com') WHERE id = 1;
```
4. Login again to get manager permissions

#### 36. Get Manager Hotels
```http
GET /api/manager/hotels
```
**Authentication**: Required (manager role)  
**Headers**: `Accept: application/json`

**Response (Success 200)**:
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Manager Hotel",
            "description": "Hotel managed by current user",
            "address": "Hotel Address",
            "country": "USA",
            "city": "New York",
            "rating": 4.5,
            "manager_id": 1,
            "rooms": [
                {
                    "id": 1,
                    "title": "Standard Room",
                    "type": "standard",
                    "price": 100.00
                }
            ]
        }
    ]
}
```

#### 37. Get Hotel Bookings (Manager)
```http
GET /api/manager/bookings
```
**Authentication**: Required (manager role)  
**Headers**: `Accept: application/json`

**Optional Filters**:
- `?hotel_id=1` - Filter by specific hotel
- `?status=confirmed` - Filter by booking status
- `?date_from=2025-01-01` - Filter from date
- `?date_to=2025-12-31` - Filter to date

**Response (Success 200)**:
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "user_id": 2,
            "room_id": 1,
            "started_at": "2025-12-01",
            "finished_at": "2025-12-05",
            "status": "confirmed",
            "price": 400,
            "user": {
                "id": 2,
                "full_name": "Guest User",
                "email": "guest@example.com"
            },
            "room": {
                "id": 1,
                "title": "Standard Room",
                "hotel": {
                    "id": 1,
                    "title": "Manager Hotel"
                }
            }
        }
    ]
}
```

#### 38. Update Booking Status
```http
PUT /api/manager/bookings/1
```
**Authentication**: Required (manager role)  
**Headers**: `Accept: application/json`, `Content-Type: application/json`

**Request Body**:
```json
{
    "status": "confirmed"
}
```

**Available Status Values**:
- `confirmed` - Booking is confirmed
- `cancelled` - Booking is cancelled  
- `pending` - Booking is pending approval

**Response (Success 200)**:
```json
{
    "success": true,
    "message": "Booking status updated"
}
```

### 🛠️ Facilities Management API

**⚠️ Admin/Manager Role Required**: All endpoints below require user role to be 'admin' or 'manager'

#### 39. Get All Facilities (Admin/Manager)
```http
GET /api/admin/facilities
```
**Authentication**: Required (admin role)  
**Headers**: `Accept: application/json`

**Response (Success 200)**:
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Wi-Fi",
            "created_at": "2025-01-15T10:30:00.000000Z",
            "updated_at": "2025-01-15T10:30:00.000000Z"
        },
        {
            "id": 2,
            "title": "Parking",
            "created_at": "2025-01-15T10:30:00.000000Z",
            "updated_at": "2025-01-15T10:30:00.000000Z"
        }
    ]
}
```

#### 40. Create Facility (Admin/Manager)
```http
POST /api/admin/facilities
```
**Authentication**: Required (admin role)  
**Headers**: `Accept: application/json`, `Content-Type: application/json`

**Request Body**:
```json
{
    "title": "Swimming Pool"
}
```

**Response (Success 201)**:
```json
{
    "success": true,
    "message": "Facility created successfully",
    "data": {
        "id": 25,
        "title": "Swimming Pool",
        "created_at": "2025-01-15T10:30:00.000000Z",
        "updated_at": "2025-01-15T10:30:00.000000Z"
    }
}
```

#### 41. Get Facility Details (Admin/Manager)
```http
GET /api/admin/facilities/25
```
**Authentication**: Required (admin role)  
**Headers**: `Accept: application/json`

**Response (Success 200)**:
```json
{
    "success": true,
    "data": {
        "id": 25,
        "title": "Swimming Pool",
        "created_at": "2025-01-15T10:30:00.000000Z",
        "updated_at": "2025-01-15T10:30:00.000000Z"
    }
}
```

#### 42. Update Facility (Admin/Manager)
```http
PUT /api/admin/facilities/25
```
**Authentication**: Required (admin role)  
**Headers**: `Accept: application/json`, `Content-Type: application/json`

**Request Body**:
```json
{
    "title": "Outdoor Swimming Pool"
}
```

**Response (Success 200)**:
```json
{
    "success": true,
    "message": "Facility updated successfully",
    "data": {
        "id": 25,
        "title": "Outdoor Swimming Pool",
        "created_at": "2025-01-15T10:30:00.000000Z",
        "updated_at": "2025-01-15T12:45:00.000000Z"
    }
}
```

#### 43. Delete Facility (Admin/Manager)
```http
DELETE /api/admin/facilities/25
```
**Authentication**: Required (admin role)  
**Headers**: `Accept: application/json`

**Response (Success 200)**:
```json
{
    "success": true,
    "message": "Facility deleted successfully"
}
```

### 📋 Admin Booking Management API

**⚠️ Admin Role Required**: All endpoints below require user role to be 'admin'

#### 44. Update Booking Status (Admin)
```http
PUT /api/admin/bookings/1
```
**Authentication**: Required (admin role)  
**Headers**: `Accept: application/json`, `Content-Type: application/json`

**Request Body**:
```json
{
    "status": "confirmed"
}
```

**Available Status Values**:
- `pending` - Booking awaiting approval
- `confirmed` - Booking is confirmed
- `cancelled` - Booking is cancelled

**Response (Success 200)**:
```json
{
    "success": true,
    "message": "Booking updated successfully"
}
```

#### 45. Approve Booking (Admin)
```http
POST /api/admin/bookings/1/approve
```
**Authentication**: Required (admin role)  
**Headers**: `Accept: application/json`

**Response (Success 200)**:
```json
{
    "success": true,
    "message": "Booking approved successfully"
}
```

#### 46. Delete Booking (Admin)
```http
DELETE /api/admin/bookings/1
```
**Authentication**: Required (admin role)  
**Headers**: `Accept: application/json`

**Response (Success 200)**:
```json
{
    "success": true,
    "message": "Booking deleted successfully"
}
```

### 🔧 Utility API

#### 47. Update User Timezone
```http
POST /api/update-timezone
```
**Authentication**: Required (session-based)  
**Headers**: `Accept: application/json`, `Content-Type: application/json`

**Request Body**:
```json
{
    "timezone": "America/New_York"
}
```

**Response (Success 200)**:
```json
{
    "success": true
}
```

#### 48. Get Current User
```http
GET /api/user
```
**Authentication**: Required (Sanctum token)  
**Headers**: `Accept: application/json`

**Response (Success 200)**:
```json
{
    "id": 1,
    "full_name": "John Doe",
    "email": "john.doe@example.com",
    "role": "user"
}
```

### 📊 API Response Format

All API endpoints follow consistent response format:

**Success Response**:
```json
{
    "success": true,
    "data": {},
    "message": "Optional success message"
}
```

**Error Response**:
```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        "field_name": ["Error description"]
    }
}
```

**HTTP Status Codes**:
- `200` - Success
- `201` - Created
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

## 🧪 Testing with Postman

### Setup Instructions

1. **Base URL**: Set as environment variable `{{base_url}}` = `http://localhost:8000`
2. **Headers**: 
   - `Accept: application/json`
   - `Content-Type: application/json` or `application/x-www-form-urlencoded`

### Quick Test Flow

#### 1. Test Authentication (Local Only)
```
POST http://localhost:8000/api/register
POST http://localhost:8000/api/login
POST http://localhost:8000/api/logout
```

#### 2. Test Public API
```
GET http://localhost:8000/api/hotels
GET http://localhost:8000/api/hotels/1
GET http://localhost:8000/api/countries
```

#### 3. Test Availability Check
```
POST http://localhost:8000/api/rooms/check-availability
Body: {"room_id": 1, "started_at": "2025-12-01", "finished_at": "2025-12-05"}
```

### Example Postman Collection

Import this JSON into Postman:

```json
{
    "info": {
        "name": "Hotel Booking Platform API",
        "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
    },
    "item": [
        {
            "name": "Authentication",
            "item": [
                {
                    "name": "Register",
                    "request": {
                        "method": "POST",
                        "header": [],
                        "body": {
                            "mode": "urlencoded",
                            "urlencoded": [
                                {"key": "full_name", "value": "John Doe"},
                                {"key": "email", "value": "john@example.com"},
                                {"key": "password", "value": "password123"},
                                {"key": "password_confirmation", "value": "password123"}
                            ]
                        },
                        "url": "{{base_url}}/api/register"
                    }
                }
            ]
        },
        {
            "name": "Hotels",
            "item": [
                {
                    "name": "Get All Hotels",
                    "request": {
                        "method": "GET",
                        "url": "{{base_url}}/api/hotels"
                    }
                }
            ]
        }
    ]
}
```

## 🐛 Troubleshooting

### API Returns HTML Instead of JSON
**Problem**: Getting HTML response from `/hotels` instead of JSON  
**Solution**: Use `/api/hotels` endpoint for JSON response

### Error 419 (CSRF Token Mismatch)
**Problem**: CSRF error when testing API  
**Solution**: 
- Use `/api/*` routes (exempt from CSRF)
- Ensure `APP_ENV=local` in `.env`
- Clear cache: `php artisan config:clear`

### Error 404 on API Routes
**Problem**: API routes not found  
**Solution**:
- Verify `api.php` is registered in `bootstrap/app.php`
- Clear route cache: `php artisan route:clear`
- Check routes: `php artisan route:list --path=api`

### Error 500 "Auth guard [sanctum] is not defined"
**Problem**: Sanctum authentication not configured for `/api/user` endpoint  
**Solution**: 
- This is expected - Sanctum is not configured in this development environment
- Use session-based authentication endpoints instead
- For production, install and configure Laravel Sanctum

### Error 422 (Validation Error) on Room Availability
**Problem**: "The room id field is required" error  
**Solution**: 
- Use `Content-Type: application/json` header
- Select "raw" and "JSON" in Postman body
- Ensure all required fields are included: `room_id`, `started_at`, `finished_at`
- Use valid date format: `YYYY-MM-DD`
- Use future dates (not past dates)

### Postman Shows "Loading..." Forever
**Problem**: Preview tab shows infinite loading  
**Solution**: Use "Body" or "Pretty" tab instead of "Preview" for JSON responses

#### Get Current User (Session-based)
```http
GET /api/profile
```
**Description**: Get currently authenticated user information  
**Authentication**: Required (session-based after login)  
**Headers**: `Accept: application/json`

**Response (Success 200)**:
```json
{
    "success": true,
    "data": {
        "id": 1,
        "full_name": "John Doe",
        "email": "john.doe@example.com",
        "phone": "+1234567890",
        "role": "user",
        "country": "USA",
        "city": "New York",
        "created_at": "2025-01-15T10:30:00.000000Z"
    }
}
```

**Response (Unauthorized 401)**:
```json
{
    "success": false,
    "message": "Unauthenticated"
}
```

**Testing Steps**:
1. First login via `POST /api/login`
2. Then call `GET /api/profile` (session will be maintained)

#### Get Current User (Sanctum - Not Available)
```http
GET /api/user
```
**Status**: ⚠️ Not functional - Sanctum not configured
**Error**: "Auth guard [sanctum] is not defined"

### 📊 API Response Format

All API endpoints follow consistent response format:

**Success Response**:
```json
{
    "success": true,
    "data": {},
    "message": "Optional success message"
}
```

**Error Response**:
```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        "field_name": ["Error description"]
    }
}
```

**HTTP Status Codes**:
- `200` - Success
- `201` - Created
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

## 🧪 Testing with Postman

### Setup Instructions

1. **Base URL**: Set as environment variable `{{base_url}}` = `http://localhost:8000`
2. **Headers**: 
   - `Accept: application/json`
   - `Content-Type: application/json` or `application/x-www-form-urlencoded`

### Quick Test Flow

#### 1. Test Authentication (Local Only)
```
POST http://localhost:8000/api/register
POST http://localhost:8000/api/login
POST http://localhost:8000/api/logout
```

#### 2. Test Public API
```
GET http://localhost:8000/api/hotels
GET http://localhost:8000/api/hotels/1
GET http://localhost:8000/api/countries
```

#### 3. Test Availability Check
```
POST http://localhost:8000/api/rooms/check-availability
Body: {"room_id": 1, "started_at": "2025-12-01", "finished_at": "2025-12-05"}
```

### Example Postman Collection

Import this JSON into Postman:

```json
{
    "info": {
        "name": "Hotel Booking Platform API",
        "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
    },
    "item": [
        {
            "name": "Authentication",
            "item": [
                {
                    "name": "Register",
                    "request": {
                        "method": "POST",
                        "header": [],
                        "body": {
                            "mode": "urlencoded",
                            "urlencoded": [
                                {"key": "full_name", "value": "John Doe"},
                                {"key": "email", "value": "john@example.com"},
                                {"key": "password", "value": "password123"},
                                {"key": "password_confirmation", "value": "password123"}
                            ]
                        },
                        "url": "{{base_url}}/api/register"
                    }
                }
            ]
        },
        {
            "name": "Hotels",
            "item": [
                {
                    "name": "Get All Hotels",
                    "request": {
                        "method": "GET",
                        "url": "{{base_url}}/api/hotels"
                    }
                }
            ]
        }
    ]
}
```

## 🛠️ Technology Stack

- **Framework**: Laravel 12 (Latest LTS)
- **PHP**: 8.2+ with modern features
- **Database**: MySQL 8.0+ with optimized queries
- **Frontend**: Blade Templates + Tailwind CSS + Alpine.js
- **API**: RESTful JSON API
- **Authentication**: Laravel Breeze with 2FA extensions
- **Testing**: PHPUnit with comprehensive test coverage
- **Caching**: Redis/File-based caching system
- **Email**: Laravel Mail with queue support

## 🧪 Complete Testing Guide

### API Endpoints Testing

#### Facilities Management API

##### Get All Facilities
```http
GET /api/facilities
Accept: application/json
```

##### Get Specific Facility
```http
GET /api/facilities/{id}
Accept: application/json
```

##### Create New Facility
```http
POST /api/facilities
Content-Type: application/json
Accept: application/json

{
    "title": "New Facility"
}
```

##### Update Facility
```http
PUT /api/facilities/{id}
Content-Type: application/json
Accept: application/json

{
    "title": "Updated Facility Name"
}
```

##### Delete Facility
```http
DELETE /api/facilities/{id}
Accept: application/json
```

#### Bookings Management API

##### Get All Bookings
```http
GET /api/bookings
Accept: application/json
```

##### Get Specific Booking
```http
GET /api/bookings/{id}
Accept: application/json
```

##### Create New Booking
```http
POST /api/bookings
Content-Type: application/json
Accept: application/json

{
    "room_id": 1,
    "user_id": 1,
    "check_in_date": "2024-12-01",
    "check_out_date": "2024-12-05",
    "total_price": 500.00,
    "status": "confirmed"
}
```

##### Update Booking
```http
PUT /api/bookings/{id}
Content-Type: application/json
Accept: application/json

{
    "status": "cancelled"
}
```

##### Delete Booking
```http
DELETE /api/bookings/{id}
Accept: application/json
```

### Web Interface Testing

#### Admin Panel - Facilities Management
- **List**: `GET /admin/facilities`
- **Create**: `GET /admin/facilities/create`
- **Store**: `POST /admin/facilities`
- **Show**: `GET /admin/facilities/{id}`
- **Edit**: `GET /admin/facilities/{id}/edit`
- **Update**: `PUT /admin/facilities/{id}`
- **Delete**: `DELETE /admin/facilities/{id}`

#### Admin Panel - Bookings Management
- **List**: `GET /admin/bookings`
- **Create**: `GET /admin/bookings/create`
- **Store**: `POST /admin/bookings`
- **Show**: `GET /admin/bookings/{id}`
- **Edit**: `GET /admin/bookings/{id}/edit`
- **Update**: `PUT /admin/bookings/{id}`
- **Delete**: `DELETE /admin/bookings/{id}`

#### Manager Panel - Facilities Management
- **List**: `GET /manager/facilities`
- **Create**: `GET /manager/facilities/create`
- **Store**: `POST /manager/facilities`
- **Show**: `GET /manager/facilities/{id}`
- **Edit**: `GET /manager/facilities/{id}/edit`
- **Update**: `PUT /manager/facilities/{id}`
- **Delete**: `DELETE /manager/facilities/{id}`

#### Manager Panel - Bookings Management
- **List**: `GET /manager/bookings`
- **Create**: `GET /manager/bookings/create`
- **Store**: `POST /manager/bookings`
- **Show**: `GET /manager/bookings/{id}`
- **Edit**: `GET /manager/bookings/{id}/edit`
- **Update**: `PUT /manager/bookings/{id}`
- **Delete**: `DELETE /manager/bookings/{id}`

### Postman/Insomnia Setup

#### Basic Configuration
- **Base URL**: `http://localhost:8000`
- **API Headers**:
  - `Content-Type: application/json`
  - `Accept: application/json`

#### Postman Collection Setup

1. Create new collection "Hotel Booking API"
2. Add environment variable `base_url` = `http://localhost:8000`
3. Create folders:
   - Facilities Management
   - Bookings Management

#### Sample Test Data

##### For Creating Facility:
```json
{
    "title": "Free Wi-Fi"
}
```

##### For Creating Booking:
```json
{
    "room_id": 1,
    "user_id": 1,
    "check_in_date": "2024-12-01",
    "check_out_date": "2024-12-05",
    "total_price": 500.00,
    "status": "confirmed"
}
```

### Validation Testing

#### Invalid Facility Data:
```json
{
    "title": ""
}
```
Expected Response: 422 Unprocessable Entity

#### Invalid Booking Data:
```json
{
    "room_id": "invalid",
    "check_in_date": "invalid-date",
    "total_price": -100
}
```
Expected Response: 422 Unprocessable Entity

### Error Testing

#### 404 Not Found
- Request non-existent ID: `GET /api/facilities/999999`

#### 500 Internal Server Error
- Can be simulated by sending incorrect data to database

### Automated Testing

#### Run PHPUnit Tests
```bash
php artisan test
```

#### Run Specific Test
```bash
php artisan test --filter FacilityTest
```

#### Run with Code Coverage
```bash
php artisan test --coverage
```

## 🌍 Multi-Language Support

### Supported Languages
- **English** (en) - USD ($)
- **Russian** (ru) - RUB (₽)
- **Arabic** (ar) - AED (د.إ) with RTL support
- **French** (fr) - EUR (€)
- **German** (de) - EUR (€)
- **Italian** (it) - EUR (€)
- **Spanish** (es) - EUR (€)

### Features
- Complete UI translation for all languages
- Automatic currency conversion based on locale
- Right-to-left (RTL) support for Arabic
- Timezone management and date formatting
- Localized email notifications

## 📊 System Statistics

After running `php artisan app:quick-setup`, your database will contain:
- **200 Hotels** with detailed descriptions and facilities
- **~1000 Rooms** with various types and pricing
- **24 Facilities** for hotels and rooms
- **~150 Bookings** with realistic data
- **~160 Reviews** with ratings and content
- **Multiple Users** including admin, manager, and regular users

## 🔒 Security Features

### General Security
- **Role-Based Access Control** (Admin, Manager, User)
- **Two-Factor Authentication** (Email/SMS)
- **CSRF Protection** on all web forms
- **SQL Injection Prevention** with Eloquent ORM
- **XSS Protection** with Blade template escaping
- **Password Hashing** with bcrypt
- **Email Verification** for new accounts
- **Rate Limiting** on API endpoints

### ⚠️ API Security Notice

**Development Only**: API authentication routes (`/api/register`, `/api/login`, `/api/logout`) are available **ONLY in local development** environment (`APP_ENV=local`).

**For Production**: 
- These routes are automatically disabled in production
- Use Laravel Sanctum for proper API authentication
- Never disable CSRF protection in production environments
- Always use proper authentication tokens for API access

**Check your .env file**:
```env
APP_ENV=local  # API test routes enabled
# APP_ENV=production  # API test routes disabled
```

## 📈 Performance Optimizations

- **Database Indexing** for fast queries
- **Eager Loading** to prevent N+1 queries
- **Caching Strategy** for frequently accessed data
- **Asset Optimization** with Vite build system
- **Image Optimization** for hotel/room photos
- **Query Optimization** with database monitoring
- **API Response Caching** for public endpoints

## 🐛 Troubleshooting

### API Returns HTML Instead of JSON
**Problem**: Getting HTML response from `/hotels` instead of JSON  
**Solution**: Use `/api/hotels` endpoint for JSON response

### Error 419 (CSRF Token Mismatch)
**Problem**: CSRF error when testing API  
**Solution**: 
- Use `/api/*` routes (exempt from CSRF)
- Ensure `APP_ENV=local` in `.env`
- Clear cache: `php artisan config:clear`

### Error 404 on API Routes
**Problem**: API routes not found  
**Solution**:
- Verify `api.php` is registered in `bootstrap/app.php`
- Clear route cache: `php artisan route:clear`
- Check routes: `php artisan route:list --path=api`

### Postman Shows "Loading..." Forever
**Problem**: Preview tab shows infinite loading  
**Solution**: Use "Body" or "Pretty" tab instead of "Preview" for JSON responses

### Arabic Translations Not Working
**Problem**: Hotel descriptions show in Russian instead of Arabic  
**Solution**: 
- Run migrations: `php artisan migrate`
- Run Arabic translations seeder: `php artisan db:seed --class=ArabicTranslationsSeeder`
- Or run complete setup: `php artisan app:quick-setup`

### Google Drive Images Not Loading
**Problem**: Hotels show Unsplash fallback images instead of Google Drive images  
**Solution**: 
- Verify `.env` settings:
  ```env
  USE_EXTERNAL_IMAGES=true
  GOOGLE_DRIVE_BASE_URL="https://lh3.googleusercontent.com/d/"
  ```
- Test image accessibility: Visit `http://localhost:8000/test_images.html`
- Clear config cache: `php artisan config:clear`
- Restart server: `php artisan serve`
- Ensure Google Drive files are publicly accessible

### Google Drive Setup Requirements
**Problem**: Images not loading from Google Drive  
**Solution**: 
1. **Make folder public**: Right-click folder → Share → "Anyone with the link"
2. **Set permissions**: Choose "Viewer" access for public
3. **Use correct URL format**: GoogleUserContent format works best
4. **Test accessibility**: Use the diagnostic page at `/test_images.html`

## 📄 Educational Purpose Statement

This **Hotel Booking Platform** was created as a comprehensive educational project to demonstrate advanced web development skills and modern software engineering practices.

### Technical Skills Demonstrated
- **Full-Stack Development**: Complete web application from database to user interface
- **RESTful API Design**: Clean, well-documented JSON API
- **Laravel Expertise**: Advanced Laravel features, best practices, and ecosystem tools
- **Multi-Language Applications**: Internationalization, localization, and cultural adaptation
- **Database Design**: Complex relationships, optimization, and data integrity
- **Security Implementation**: Authentication, authorization, and data protection
- **Testing Methodologies**: Unit testing, integration testing, and test-driven development
- **Performance Optimization**: Caching strategies, query optimization, and scalability
- **Modern Frontend**: Responsive design, progressive enhancement, and user experience
- **API Documentation**: Complete, clear documentation for developers

### Professional Development Showcase
- **Code Quality**: Clean code principles, SOLID design patterns, and maintainable architecture
- **Documentation**: Comprehensive documentation, code comments, and technical writing
- **Project Management**: Feature planning, version control, and release management
- **Problem Solving**: Complex business logic implementation and edge case handling
- **Industry Standards**: Following established conventions and best practices

### Not for Commercial Use
This project is specifically created for educational and portfolio purposes. It demonstrates technical capabilities and serves as a learning resource for web development concepts, but is not intended for commercial deployment or production use.

---

**Project Status**: Educational Demonstration  
**Purpose**: Skills Showcase & Learning Resource  
**Maintenance**: Documentation and Examples Only

*This project represents a comprehensive example of modern web application development using Laravel and demonstrates the developer's ability to create complex, feature-rich applications following industry best practices.*

## 📚 Additional Resources

- **Web Routes**: See source code for complete web (browser) routes
- **Database Schema**: Run migrations to see complete database structure
- **Code Examples**: Check controller files for implementation details
- **Testing**: Run `php artisan test` for comprehensive test suite

---
