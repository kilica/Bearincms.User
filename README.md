# BearinUser

Bearin CMS User Module - A comprehensive user management system built with BEAR.Sunday framework.

## Features

- **User Registration**: Complete user registration with email validation
- **Authentication**: Secure login/logout functionality with session management
- **Password Management**: Password reminder and reset functionality
- **Role-based Access Control**: Support for admin, editor, viewer, and guest roles
- **User Profiles**: Comprehensive user profile management
- **Admin Interface**: Full CRUD operations for user management

## Database Schema

### Users Table
- `id` - Primary key
- `email` - Unique email address
- `password` - Hashed password
- `status` - Account status (active, inactive, suspended)
- `created_at`, `updated_at`, `deleted_at` - Timestamps
- `last_logined_at` - Last login timestamp

### Roles Table
- `id` - Primary key
- `user_id` - Foreign key to users table
- `role` - User role (admin, editor, viewer, guest)

### Sessions Table
- Session management for authenticated users

### Profiles Table
- `user_id` - Foreign key to users table
- `name` - Full name
- `nickname` - Display name
- `avatar_image` - Profile image URL
- `profile` - User bio/description

## Installation

1. Install dependencies:
```bash
composer install
```

2. Set up database:
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE bearin_user"

# Run migrations
mysql -u root -p bearin_user < var/db/schema.sql
```

3. Configure database connection in `src/Module/AppModule.php`

## API Endpoints

### Authentication
- `POST /auth/login` - User login
- `DELETE /auth/logout` - User logout

### User Management
- `GET /users` - List all users (admin only)
- `GET /users/{id}` - Get user details (admin only)
- `POST /users` - Create new user
- `PUT /users/{id}` - Update user (admin only)
- `DELETE /users/{id}` - Delete user (admin only)

### Password Reset
- `POST /password-reset` - Request password reset
- `PUT /password-reset` - Reset password with token

### User Profiles
- `GET /profile/{userId}` - Get user profile
- `PUT /profile/{userId}` - Update user profile

## Usage

### CLI Access
```bash
# Console access
php bin/app.php options /users

# Page access
php bin/page.php get '/users'
```

### Web Access
Configure your web server to point to the `public/` directory.

## Default Admin User

- Email: `admin@bearin.cms`
- Password: `password`

**Important**: Change the default admin password after installation.

## Testing

Run tests with PHPUnit:
```bash
composer test
```

## Security Features

- Password hashing with bcrypt
- Session-based authentication
- Role-based authorization
- Input validation and sanitization
- SQL injection prevention
- CSRF protection ready

## Requirements

- PHP 8.1+
- MySQL 5.7+ or MariaDB 10.2+
- Composer

## License

MIT License
