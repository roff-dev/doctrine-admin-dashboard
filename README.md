# Admin Dashboard with Doctrine ORM

This project demonstrates a robust enterprise-level admin dashboard built with PHP and Doctrine ORM, designed to showcase how Doctrine can be effectively implemented in real-world business applications. The dashboard provides a comprehensive solution for businesses to manage their organizational structure through a secure, centralized portal that offers complete oversight of companies and their workforce.

While using fabricated data for demonstration purposes, the application implements production-ready authentication, data validation, and CRUD operations that mirror real-world business requirements. System administrators can use this tool to gather critical workforce information, manage company resources, and make data-driven decisions based on the centralized information provided.

The project serves dual purposes: demonstrating technical proficiency with Doctrine ORM and PHP while offering a practical solution to the universal business need for internal management systems. The secure authentication system ensures that sensitive employee and company data remains accessible only to authorized personnel, maintaining data privacy and security standards that would be expected in an enterprise environment.

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

Make sure to run these commands in a terminal that points to the root directory of this project

1. Clone the repository
2. Install dependencies:
   ```
   composer install
   ```
3. Configure database connection in `config/doctrine.php` (Schema creates the db so default config can be used unless you require a specific setup)
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

## Roadmap

While this project is currently in a working state, I am committed to making constant improvements and implementing new features. This roadmap outlines the planned enhancements and additions that will further expand the dashboard's capabilities and improve user experience.

Future features and improvements:

- Improved entity details (Employee tasks, stats, and tickets)
- Individual user profiles and pages (Companies and Employees have personalised profiles only showing related data)
- Improved dashboard view (quick access statistic visuals)
- Admin individual user management (manage user profiles: change permissions, tasks, and tickets)
- Cron Jobs for weekly reports

- Repo CI/CD for Pull Requests

These planned additions aim to make this admin dashboard even more powerful and versatile for real-world business applications. Feel free to suggest additional features or improvements by opening an issue.