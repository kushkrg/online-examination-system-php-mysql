---
title: "Build a Full-Stack Online Examination System with PHP & MySQL — Complete Guide"
slug: "online-examination-system-php-mysql"
description: "A complete, production-ready Online Examination System built with pure PHP (MVC), MySQL, and Bootstrap 5. Includes admin panel, student portal, real-time proctoring, CSV question import, role-based access, and AJAX-powered dashboards. Free & open source."
tags:
  - php
  - mysql
  - online-exam-system
  - mvc
  - bootstrap5
  - admin-panel
  - open-source
  - web-development
  - exam-portal
  - student-management
author: "Kush"
date: "2026-09-08"
category: "Web Development"
readTime: "12 min read"
cover_image: "/images/online-exam-system-cover.png"
canonical_url: "https://yourblog.com/online-examination-system-php-mysql"
---

# 🚀 Online Examination System — Full-Stack PHP & MySQL

> A **production-grade**, feature-rich Online Examination System built from scratch using **pure PHP (MVC architecture)**, **MySQL**, and **Bootstrap 5**. No frameworks. No magic. Just clean, well-structured code you can learn from and deploy anywhere.

[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat&logo=mysql&logoColor=white)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## 📋 Table of Contents

1. [Project Overview](#project-overview)
2. [Live Demo Features](#live-demo-features)
3. [Tech Stack](#tech-stack)
4. [Project Architecture](#project-architecture)
5. [Database Schema](#database-schema)
6. [Module Breakdown](#module-breakdown)
7. [Installation & Local Setup](#installation--local-setup)
8. [Deployment Guide](#deployment-guide)
   - [Shared Hosting (cPanel)](#option-1-shared-hosting-cpanel)
   - [VPS / Ubuntu Server](#option-2-vps--ubuntu-server)
   - [Docker](#option-3-docker)
9. [Configuration Reference](#configuration-reference)
10. [CSV Question Import Format](#csv-question-import-format)
11. [Default Credentials](#default-credentials)
12. [Screenshots](#screenshots)
13. [Roadmap](#roadmap)
14. [Contributing](#contributing)
15. [License](#license)

---

## Project Overview

This project is a **complete Online Examination System** that allows institutions, coaching centres, and educators to create and conduct exams online. Students can register, browse available exams, attempt them in a secure proctored environment, and view instant results.

### Why this project?

Most open-source exam systems are either too heavy (WordPress plugins, Laravel monoliths) or too minimal. This system hits the sweet spot — it's **lightweight, self-contained, easy to deploy**, and feature-complete enough for real-world use.

### Who is it for?

| Audience | Use Case |
|---|---|
| Educators & Schools | Conduct class tests and final exams online |
| Coaching Institutes | Create mock exams and track student performance |
| HR Departments | Screening tests for job applicants |
| Developers | Learn MVC architecture with pure PHP |
| YouTubers / Bloggers | Build & showcase as a portfolio project |

---

## Live Demo Features

### 🌐 Public Homepage (`/`)
- Lists all published exams with live status indicators
- Registration and login CTAs
- Exam details: duration, marks, pass criteria, negative marking info
- Smart buttons based on user role and exam status (Live / Upcoming / Ended)

### 🛡️ Admin Panel (`/admin/dashboard`)
- **Dashboard** — Stats overview (total exams, users, submissions, pass rate, avg score, integrity flags)
- **Exams Module** — Card grid view with live badge, pass rate, attempt count; search & status filter
- **Questions Module** — Add MCQ questions with dynamic options (2–∞), image upload per question, AJAX pagination & search, bulk CSV import
- **Students Module** — View all registered students with exam activity stats, status toggle, profile modal with exam history
- **Results Module** — Pass/Fail donut chart, score distribution bar chart, filters by exam/result/name, answer review modal per attempt
- **Users Module** — Manage all roles (Admin / Examiner / Student), create/edit/delete users, role-based colour themes

### 🎓 Student Portal (`/student/dashboard`)
- Browse available exams
- Fullscreen exam engine with auto-save answers
- Tab-switch violation detection & logging
- Timer with auto-submit
- Instant result page with score breakdown

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend Language | PHP 8.1+ |
| Architecture | Custom MVC (no framework) |
| Database | MySQL 8.0+ |
| Frontend | Bootstrap 5.3, Vanilla JS |
| Charts | Chart.js 4.4 |
| Icons | Bootstrap Icons 1.11 |
| Fonts | Google Fonts — Inter |
| Server (Dev) | PHP Built-in Server |
| Server (Prod) | Apache / Nginx |

---

## Project Architecture

```
Online Examination System/
├── index.php                    # Front controller & router
├── database.sql                 # Complete DB schema + seed data
├── README.md
│
├── app/
│   ├── Config/
│   │   └── Database.php         # PDO connection config
│   │
│   ├── Controllers/
│   │   ├── HomeController.php   # Public homepage
│   │   ├── AuthController.php   # Login / Register / Logout
│   │   ├── AdminController.php  # All admin operations (CRUD)
│   │   └── StudentController.php# Exam engine, results
│   │
│   ├── Models/
│   │   ├── User.php             # User CRUD, stats, role management
│   │   ├── Exam.php             # Exam CRUD with aggregated stats
│   │   ├── Question.php         # MCQ CRUD with options, image
│   │   └── Attempt.php          # Exam attempts, scoring, evaluation
│   │
│   ├── Views/
│   │   ├── home.php             # Public landing page
│   │   ├── layouts/
│   │   │   └── admin.php        # Admin shell layout + sidebar
│   │   ├── auth/
│   │   │   ├── login.php
│   │   │   └── register.php
│   │   ├── admin/
│   │   │   ├── dashboard.php
│   │   │   ├── exams/           # index, create, edit, questions, question_edit
│   │   │   ├── students/        # index, edit
│   │   │   ├── results/         # index
│   │   │   └── users/           # index, create, edit
│   │   └── student/             # dashboard, exam views, result
│   │
│   └── Helpers/
│       └── AuthHelper.php       # Session-based role guard
│
└── public/
    └── uploads/
        └── questions/           # Uploaded question images
```

### MVC Request Lifecycle

```
Browser Request
      │
      ▼
  index.php  ──── parses URI ────► Router (switch/case)
      │
      ▼
  Controller  ──── calls Model ──► Database (PDO)
      │                                  │
      │         ◄─── data ───────────────┘
      ▼
   View (PHP template)
      │
      ▼
  HTML Response
```

---

## Database Schema

The system uses **5 core tables** with foreign key constraints and `ON DELETE CASCADE` for data integrity.

```sql
-- Users table (all roles)
CREATE TABLE users (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(100) NOT NULL,
    email        VARCHAR(150) UNIQUE NOT NULL,
    password     VARCHAR(255) NOT NULL,          -- bcrypt hashed
    role         ENUM('admin','examiner','student') DEFAULT 'student',
    city         VARCHAR(100),
    status       ENUM('active','inactive') DEFAULT 'active',
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Exams table
CREATE TABLE exams (
    id                      INT AUTO_INCREMENT PRIMARY KEY,
    title                   VARCHAR(255) NOT NULL,
    description             TEXT,
    duration_minutes        INT NOT NULL,
    start_time              DATETIME NOT NULL,
    end_time                DATETIME NOT NULL,
    total_marks             DECIMAL(8,2) DEFAULT 100,
    passing_marks           DECIMAL(8,2) DEFAULT 40,
    negative_marking_ratio  DECIMAL(4,2) DEFAULT 0,
    status                  ENUM('draft','published','completed') DEFAULT 'draft',
    created_by              INT,
    created_at              TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Questions table
CREATE TABLE questions (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    exam_id        INT NOT NULL,
    question_text  TEXT NOT NULL,
    image_url      VARCHAR(500),                 -- optional image per question
    marks          DECIMAL(5,2) DEFAULT 1.00,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE
);

-- Options table (MCQ choices)
CREATE TABLE options (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    question_id  INT NOT NULL,
    option_text  VARCHAR(500) NOT NULL,
    is_correct   TINYINT(1) DEFAULT 0,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

-- Exam Attempts table
CREATE TABLE exam_attempts (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT NOT NULL,
    exam_id      INT NOT NULL,
    start_time   DATETIME,
    end_time     DATETIME,
    score        DECIMAL(8,2),
    status       ENUM('in_progress','submitted','evaluated') DEFAULT 'in_progress',
    tab_switches INT DEFAULT 0,                  -- proctoring violations
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE
);

-- Student Answers table
CREATE TABLE student_answers (
    id                 INT AUTO_INCREMENT PRIMARY KEY,
    attempt_id         INT NOT NULL,
    question_id        INT NOT NULL,
    selected_option_id INT,
    marks_obtained     DECIMAL(5,2) DEFAULT 0,
    FOREIGN KEY (attempt_id) REFERENCES exam_attempts(id) ON DELETE CASCADE
);
```

---

## Module Breakdown

### 1. Authentication System
- Session-based login/logout
- bcrypt password hashing
- Role-based access guard (`AuthHelper::requireRole()`)
- Duplicate email check on registration

### 2. Exam Management
- Create, Edit, Delete exams with draft/published/completed states
- Set duration, marks, passing marks, negative marking ratio, start/end times
- Real-time **LIVE** badge when exam is currently active
- View question count, attempt count, pass rate per exam

### 3. Question Management
- Add MCQ questions with **2 to unlimited options** (dynamic JS)
- Upload an image per question (PNG/JPG/GIF)
- **Bulk import via CSV** — import hundreds of questions at once
- **Download sample CSV** template
- AJAX-powered question list with pagination and live search
- Edit/Delete individual questions

### 4. Student Management
- View all registered students with exam activity data
- Toggle student status (Active/Inactive) via AJAX
- Student profile modal showing exam history, avg score, violation count
- Edit student details and reset password

### 5. Results & Analytics
- Pass/Fail donut chart + score distribution bar chart (Chart.js)
- Filter results by exam, result (pass/fail), student name
- Per-attempt **Answer Review Modal** — see every question with student's answer vs correct answer
- Integrity flag count per attempt

### 6. User Management
- Full CRUD for all user roles: Admin, Examiner, Student
- Role-colored profile pages (purple=admin, green=student, amber=examiner)
- Cannot delete the last active admin (safety guard)
- Toggle status AJAX (live badge update without page reload)

### 7. Exam Engine (Student Side)
- Fullscreen enforcement on exam start
- Auto-save answers via AJAX on every selection
- Tab-switch / focus-loss detection → violation logged to DB
- Countdown timer with auto-submit when time expires
- Instant result page with score, correct/incorrect breakdown, pass/fail verdict

---

## Installation & Local Setup

### Prerequisites

| Requirement | Version |
|---|---|
| PHP | 8.1 or higher |
| MySQL | 8.0 or higher |
| Composer | Not required |
| Web Server | Apache / Nginx / PHP built-in |

### Step 1 — Clone the Repository

```bash
git clone https://github.com/yourusername/online-examination-system.git
cd online-examination-system
```

### Step 2 — Create the Database

```bash
mysql -u root -p
```

```sql
CREATE DATABASE exam_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

```bash
mysql -u root -p exam_system < database.sql
```

### Step 3 — Configure Database Connection

Open `app/Config/Database.php` and update:

```php
private $host     = 'localhost';
private $db_name  = 'exam_system';       // your DB name
private $username = 'root';              // your MySQL user
private $password = '';                  // your MySQL password
```

### Step 4 — Create Upload Directory

```bash
mkdir -p public/uploads/questions
chmod 755 public/uploads/questions
```

### Step 5 — Start the Development Server

```bash
php -S localhost:8000
```

Open your browser → **http://localhost:8000**

---

## Deployment Guide

### Option 1: Shared Hosting (cPanel)

This is the **easiest** option — suitable for beginners.

#### Step 1 — Prepare your files
```bash
# Zip your project folder
zip -r exam-system.zip "Online Examination System/"
```

#### Step 2 — Upload via File Manager
1. Log in to **cPanel**
2. Open **File Manager** → navigate to `public_html/`
3. Upload and extract `exam-system.zip`
4. Move the contents so `index.php` is directly inside `public_html/`

#### Step 3 — Create MySQL Database in cPanel
1. Go to **MySQL Databases**
2. Create a new database, e.g., `youruser_examdb`
3. Create a MySQL user with a strong password
4. **Grant ALL privileges** to the user on that database

#### Step 4 — Import the SQL Schema
1. Open **phpMyAdmin**
2. Select your database
3. Click **Import** tab → choose `database.sql` → click **Go**

#### Step 5 — Update Database Config
Edit `app/Config/Database.php` with your cPanel DB credentials:
```php
private $host     = 'localhost';
private $db_name  = 'youruser_examdb';
private $username = 'youruser_dbuser';
private $password = 'your_strong_password';
```

#### Step 6 — Set up `.htaccess` (Apache)

Create or verify `public_html/.htaccess`:
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

#### Step 7 — Set folder permissions
```
public/uploads/questions/   →  755
app/                        →  644 (files), 755 (dirs)
```

✅ Visit your domain — the homepage should load!

---

### Option 2: VPS / Ubuntu Server

#### Prerequisites
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y apache2 mysql-server php8.1 php8.1-mysql php8.1-pdo libapache2-mod-php
sudo a2enmod rewrite
```

#### Step 1 — Upload your project
```bash
# Using SCP from your local machine
scp -r "Online Examination System/" user@your-server-ip:/var/www/exam-system
```

#### Step 2 — Set permissions
```bash
sudo chown -R www-data:www-data /var/www/exam-system
sudo find /var/www/exam-system -type d -exec chmod 755 {} \;
sudo find /var/www/exam-system -type f -exec chmod 644 {} \;
sudo chmod -R 775 /var/www/exam-system/public/uploads
```

#### Step 3 — Create MySQL database
```bash
sudo mysql -u root -p
```
```sql
CREATE DATABASE exam_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'examuser'@'localhost' IDENTIFIED BY 'StrongPass@123';
GRANT ALL PRIVILEGES ON exam_system.* TO 'examuser'@'localhost';
FLUSH PRIVILEGES;
EXIT;

mysql -u examuser -p exam_system < /var/www/exam-system/database.sql
```

#### Step 4 — Configure Apache Virtual Host
```bash
sudo nano /etc/apache2/sites-available/exam-system.conf
```

Paste:
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/exam-system

    <Directory /var/www/exam-system>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/exam-system-error.log
    CustomLog ${APACHE_LOG_DIR}/exam-system-access.log combined
</VirtualHost>
```

```bash
sudo a2ensite exam-system.conf
sudo systemctl restart apache2
```

#### Step 5 — SSL with Let's Encrypt (Recommended)
```bash
sudo apt install -y certbot python3-certbot-apache
sudo certbot --apache -d yourdomain.com
```

✅ Your system is now live at `https://yourdomain.com`!

---

### Option 3: Docker

> For developers who want a containerised, reproducible environment.

Create `docker-compose.yml` in the project root:

```yaml
version: '3.8'

services:
  app:
    image: php:8.1-apache
    container_name: exam_app
    volumes:
      - .:/var/www/html
    ports:
      - "8080:80"
    depends_on:
      - db
    environment:
      - APACHE_DOCUMENT_ROOT=/var/www/html

  db:
    image: mysql:8.0
    container_name: exam_db
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: rootpass
      MYSQL_DATABASE: exam_system
      MYSQL_USER: examuser
      MYSQL_PASSWORD: exampass
    volumes:
      - db_data:/var/lib/mysql
      - ./database.sql:/docker-entrypoint-initdb.d/database.sql
    ports:
      - "3306:3306"

volumes:
  db_data:
```

Update `app/Config/Database.php`:
```php
private $host     = 'db';           // Docker service name
private $db_name  = 'exam_system';
private $username = 'examuser';
private $password = 'exampass';
```

```bash
docker-compose up -d
```

Open → **http://localhost:8080**

---

## Configuration Reference

### `app/Config/Database.php`

```php
private $host     = 'localhost';    // DB host
private $db_name  = 'exam_system';  // DB name
private $username = 'root';         // DB user
private $password = '';             // DB password
private $charset  = 'utf8mb4';      // Always use utf8mb4
```

### File Upload Config

Image uploads are stored in:
```
public/uploads/questions/
```

Supported formats: **JPG, PNG, GIF, WebP**  
Maximum size: Controlled by your `php.ini`:
```ini
upload_max_filesize = 10M
post_max_size = 12M
```

---

## CSV Question Import Format

To bulk-import questions, use the following CSV format:

```csv
question_text,marks,option_a,option_b,option_c,option_d,correct_option
What is the capital of France?,1,Berlin,Paris,Rome,Madrid,B
Which planet is closest to the sun?,2,Earth,Venus,Mercury,Mars,C
True or False: Water is H2O.,1,True,False,,,A
PHP stands for?,1,Personal Home Page,Hypertext Preprocessor,Public Homepage Protocol,Private Handle Protocol,B
```

**Rules:**
- First row is the **header** — always keep it, it will be skipped
- `correct_option` must be **A, B, C, or D**
- `option_c` and `option_d` can be **empty** (for True/False questions)
- `marks` defaults to `1.0` if left empty or invalid
- Blank question rows are silently skipped

📥 Download the sample CSV template from **Admin → Questions → Import CSV → Download Sample CSV**.

---

## Default Credentials

After importing `database.sql`, use these credentials to log in:

| Role | Email | Password |
|---|---|---|
| Admin | `admin@exam.com` | `password` |
| Student | Register via the homepage | — |

> ⚠️ **Security Warning:** Change the default admin password immediately after first login in production.

---

## Screenshots

> _(Add your screenshots here)_

| Page | Description |
|---|---|
| `homepage.png` | Public landing page with exam cards |
| `admin-dashboard.png` | Admin dashboard with stats & charts |
| `exam-management.png` | Exam cards grid with live badge |
| `question-management.png` | Add question + CSV import tabs |
| `results-analytics.png` | Results with donut chart + answer review |
| `student-exam.png` | Student exam engine in fullscreen |
| `users-management.png` | User management with role badges |

---

## Roadmap

- [x] MVC architecture with custom router
- [x] Role-based authentication (Admin / Examiner / Student)
- [x] Full exam CRUD with published/draft/completed states
- [x] MCQ questions with dynamic options (2 to unlimited)
- [x] Per-question image upload
- [x] Bulk CSV question import
- [x] Real-time exam engine with auto-save
- [x] Tab-switch proctoring & violation logging
- [x] AJAX-powered admin tables with pagination & search
- [x] Results analytics with Chart.js charts
- [x] Per-attempt answer review modal
- [x] Public homepage with exam listing
- [ ] Email notifications (exam reminders, result emails)
- [ ] Exportable result reports (PDF / Excel)
- [ ] Examiner role with restricted access
- [ ] Question bank / tag-based question pools
- [ ] Randomise question order per student
- [ ] Multi-language support
- [ ] REST API for mobile app integration

---

## Contributing

Contributions are welcome! Here's how to get started:

```bash
# 1. Fork the repository
# 2. Create a feature branch
git checkout -b feature/your-feature-name

# 3. Make your changes
# 4. Commit with a clear message
git commit -m "feat: add email notification for exam results"

# 5. Push and open a Pull Request
git push origin feature/your-feature-name
```

Please follow the existing code style and add comments for complex logic.

---

## License

This project is licensed under the **MIT License** — free to use, modify, and distribute for personal and commercial projects.

```
MIT License

Copyright (c) 2025

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
```

---

## Author

Built with ❤️ by **Lavkush K.**

- 🎬 YouTube: [CodingCush](https://youtube.com/@codingcush)
- 📝 Blog: [CodingCush](https://codingcush.com)
- 🐙 GitHub: [CodingCush](https://github.com/kushkrg)

---

*If this project helped you, please ⭐ star the repository and subscribe to the YouTube channel for more full-stack PHP tutorials!*
