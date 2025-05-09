# 📘 Laravel Project Setup Guide

This guide walks you through setting up, running, and testing a Laravel application from scratch.

---

## 🛠️ Step 1: Project Setup

First, clone the project and install all necessary dependencies.

```bash
git clone https://github.com/Pirakalathan01/be-project.git
cd be-project
composer install
cp .env.example .env
php artisan key:generate
```

## 🗄️ Step 2: Database Configuration
Edit the .env file and update your database credentials:

env
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```
Make sure your MySQL server is running and the database is created.

## 🧱 Step 3: Run Migrations
Run database migrations to create necessary tables:
```bash
php artisan migrate
```
## 🌐 Step 4: Start the Development Server
Run the Laravel application locally using:
```bash
php artisan serve
```
By default, your app will be accessible at:
http://localhost:8000

## 🧪 Step 5: Run Unit Tests
If the project includes tests, you can run them with:
```bash
php artisan test
```
