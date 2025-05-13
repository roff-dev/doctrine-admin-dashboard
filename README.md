# Admin Dashboard with Doctrine ORM

This project is an admin dashboard built with PHP and Doctrine ORM that allows managing companies and employees.

## Features

- User authentication
- CRUD operations for Companies
- CRUD operations for Employees
- File upload for company logos
- Responsive design with Bootstrap

## Requirements

- PHP 8.0 or higher
- MySQL database
- Composer

## Installation

1. Clone the repository
2. Install dependencies:
   ```
   composer install
   ```
3. Configure database connection in `config/doctrine.php`
4. Create database schema:
   ```
   php bin/create-schema.php
   ```
5. Create admin user:
   ```
   php bin/create-admin-user.php
   ```
6. Run database seeder:
   ```
   php bin/load-fixtures.php
   ```
## Usage

1. Start a PHP development server:
   ```
   php -S localhost:8000 -t public
   ```
2. Access the admin panel at `http://localhost:8000`
3. Login with:
   - Email: admin@admin.com
   - Password: password

## Database Structure

### Users Table
- id (auto-increment)
- email (required, unique)
- password (hashed)
- created_at
- updated_at

### Companies Table
- id (auto-increment)
- name (required)
- email
- logo (minimum 100x100)
- website
- created_at
- updated_at

### Employees Table
- id (auto-increment)
- first_name (required)
- last_name (required)
- company_id (foreign key to Companies)
- email
- phone
- created_at
- updated_at 