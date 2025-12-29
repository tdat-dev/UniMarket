<p align="center">
  <img src="public/images/UniMarketHead.svg" alt="UniMarket Logo" width="100" height="100">
</p>

<h1 align="center">UniMarket</h1>

<p align="center">
  <strong>Nền Tảng Thương Mại Điện Tử Dành Cho Sinh Viên</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/version-1.0.0-blue.svg" alt="Version">
  <img src="https://img.shields.io/badge/build-passing-brightgreen.svg" alt="Build">
  <img src="https://img.shields.io/badge/php-8.0+-777BB4.svg" alt="PHP">
  <img src="https://img.shields.io/badge/mysql-8.0+-4479A1.svg" alt="MySQL">
  <img src="https://img.shields.io/badge/tailwind-3.x-38B2AC.svg" alt="Tailwind">
</p>

<p align="center">
  <code>🔒 CONFIDENTIAL - Internal Use Only</code>
</p>

---

## Table of Contents

1. [Overview](#overview)
2. [System Requirements](#system-requirements)
3. [Architecture](#architecture)
4. [Installation](#installation)
5. [Project Structure](#project-structure)
6. [Database Schema](#database-schema)
7. [Development Workflow](#development-workflow)
8. [Team Members](#team-members)
9. [Task Assignment](#task-assignment)
10. [Project Timeline](#project-timeline)
11. [Changelog](#changelog)

---

## Overview

### Project Description

**UniMarket** là nền tảng thương mại điện tử C2C (Consumer-to-Consumer) được phát triển dành riêng cho sinh viên Việt Nam. Hệ thống cho phép sinh viên đăng bán, tìm kiếm và mua các sản phẩm đã qua sử dụng với giá cả phải chăng.

### Business Objectives

| Objective              | Description                        | Priority |
| ---------------------- | ---------------------------------- | :------: |
| User Authentication    | Hệ thống đăng ký/đăng nhập an toàn |    P0    |
| Product Listing        | Đăng bán sản phẩm với hình ảnh     |    P0    |
| Search & Filter        | Tìm kiếm và lọc sản phẩm           |    P1    |
| User Profile           | Quản lý thông tin cá nhân          |    P1    |
| Product Recommendation | Gợi ý sản phẩm phù hợp             |    P2    |

### Key Features

```
┌─────────────────────────────────────────────────────────────┐
│                        UNIMARKET                            │
├─────────────────┬─────────────────┬─────────────────────────┤
│   AUTH MODULE   │  PRODUCT MODULE │     USER MODULE         │
├─────────────────┼─────────────────┼─────────────────────────┤
│ • Register      │ • Create        │ • Profile Management    │
│ • Login         │ • Read (List)   │ • Order History         │
│ • Logout        │ • Update        │ • Wishlist              │
│ • Password Reset│ • Delete        │ • Notifications         │
└─────────────────┴─────────────────┴─────────────────────────┘
```

---

## System Requirements

### Minimum Requirements

| Component    | Requirement                               |
| ------------ | ----------------------------------------- |
| **OS**       | Windows 10 / macOS 10.15+ / Ubuntu 20.04+ |
| **PHP**      | >= 8.0                                    |
| **MySQL**    | >= 8.0                                    |
| **Composer** | >= 2.0                                    |
| **Node.js**  | >= 16.0                                   |
| **RAM**      | 4GB                                       |
| **Storage**  | 1GB                                       |

### Development Tools

| Tool            | Purpose                  | Required |
| --------------- | ------------------------ | :------: |
| Laragon         | Local server environment |    ✅    |
| VS Code         | Code editor              |    ✅    |
| Git             | Version control          |    ✅    |
| MySQL Workbench | Database management      | Optional |
| Postman         | API testing              | Optional |

---

## Architecture

### System Architecture

```
┌──────────────────────────────────────────────────────────────┐
│                         CLIENT                               │
│                    (Web Browser)                             │
└─────────────────────────┬────────────────────────────────────┘
                          │ HTTP/HTTPS
                          ▼
┌──────────────────────────────────────────────────────────────┐
│                      WEB SERVER                              │
│                  (Apache/Nginx)                              │
└─────────────────────────┬────────────────────────────────────┘
                          │
                          ▼
┌──────────────────────────────────────────────────────────────┐
│                     APPLICATION                              │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐   │
│  │   Router    │──│ Controllers │──│     Services        │   │
│  └─────────────┘  └─────────────┘  └─────────────────────┘   │
│                          │                    │              │
│                          ▼                    ▼              │
│                   ┌─────────────┐      ┌───────────┐         │
│                   │   Models    │──────│  Database │         │
│                   └─────────────┘      │  (MySQL)  │         │
│                                        └───────────┘         │
└──────────────────────────────────────────────────────────────┘
```

### Technology Stack

```
┌─────────────────────────────────────────────────────────────┐
│                      FRONTEND                               │
│  ┌───────────┐  ┌───────────┐  ┌───────────┐  ┌──────────┐  │
│  │   HTML5   │  │   CSS3    │  │    JS     │  │  Assets  │  │
│  └───────────┘  └───────────┘  └───────────┘  └──────────┘  │
│  ┌─────────────────────────────────────────────────────────┐│
│  │              Tailwind CSS 3.x                           ││
│  └─────────────────────────────────────────────────────────┘│
│  ┌─────────────────────────────────────────────────────────┐│
│  │              Font Awesome 6.x                           ││
│  └─────────────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                      BACKEND                                │
│  ┌─────────────────────────────────────────────────────────┐│
│  │                    PHP 8.0+                             ││
│  └─────────────────────────────────────────────────────────┘│
│  ┌───────────┐  ┌───────────┐  ┌───────────┐  ┌──────────┐  │
│  │  Router   │  │Controllers│  │  Models   │  │ Services │  │
│  └───────────┘  └───────────┘  └───────────┘  └──────────┘  │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                      DATABASE                               │
│  ┌─────────────────────────────────────────────────────────┐│
│  │                   MySQL 8.0+                            ││
│  └─────────────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────┘
```

---

## Installation

### Step 1: Clone Repository

```bash
git clone <repository-url>
cd UniMarket
```

### Step 2: Install Dependencies

```bash
# PHP dependencies
composer install

# Node.js dependencies
npm install
```

### Step 3: Environment Configuration

```bash
# Copy environment file
cp .env.example .env
```

Edit `.env` file:

```env
# Application
APP_NAME=UniMarket
APP_ENV=local
APP_DEBUG=true
APP_URL=http://unimarket.test

# Database
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=unimarket
DB_USERNAME=root
DB_PASSWORD=
```

### Step 4: Database Setup

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE unimarket CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import schema
mysql -u root -p unimarket < db.sql
```

### Step 5: Build Assets

```bash
# Development (with watch)
npm run dev

# Production
npm run build
```

### Step 6: Start Development Server

**Option A: Using Laragon**

- Start Laragon
- Access: `http://unimarket.test`

**Option B: Using PHP Built-in Server**

```bash
php -S localhost:8000 -t public
```

### Verification

| Check                 | Expected Result |
| --------------------- | --------------- |
| Homepage loads        | ✅              |
| CSS styles applied    | ✅              |
| Database connected    | ✅              |
| Login page accessible | ✅              |

---

## Project Structure

```
UniMarket/
│
├── 📁 app/                          # Application Source Code
│   ├── 📁 Controllers/              # Request Handlers
│   │   ├── AuthController.php       # Authentication logic
│   │   ├── BaseController.php       # Base controller class
│   │   ├── HomeController.php       # Homepage logic
│   │   └── ProductController.php    # Product CRUD
│   │
│   ├── 📁 Core/                     # Framework Core
│   │   ├── App.php                  # Application bootstrap
│   │   ├── Database.php             # Database connection
│   │   └── Router.php               # URL routing
│   │
│   ├── 📁 Models/                   # Data Models (ORM)
│   │   ├── BaseModel.php            # Base model class
│   │   ├── Product.php              # Product model
│   │   └── User.php                 # User model
│   │
│   └── 📁 Services/                 # Business Logic
│       └── RecommendationService.php
│
├── 📁 config/                       # Configuration Files
│   ├── app.php                      # App configuration
│   └── database.php                 # Database configuration
│
├── 📁 public/                       # Public Directory (Web Root)
│   ├── 📁 css/                      # Compiled CSS
│   ├── 📁 images/                   # Image assets
│   ├── 📁 js/                       # JavaScript files
│   ├── 📁 uploads/                  # User uploads
│   └── index.php                    # Entry point
│
├── 📁 resources/                    # Source Resources
│   ├── 📁 css/
│   │   └── app.css                  # Tailwind source
│   ├── 📁 lang/
│   │   └── lang.php                 # Language strings
│   └── 📁 views/                    # View Templates
│       ├── 📁 auth/                 # Auth pages
│       │   ├── login.php
│       │   └── register.php
│       ├── 📁 home/                 # Homepage
│       ├── 📁 layouts/              # Layout templates
│       └── 📁 partials/             # Reusable components
│           ├── header.php
│           └── footer.php
│
├── 📁 routes/                       # Route Definitions
│   └── web.php                      # Web routes
│
├── 📁 vendor/                       # Composer Dependencies
│
├── .env                             # Environment Variables
├── .env.example                     # Environment Template
├── .gitignore                       # Git Ignore Rules
├── composer.json                    # PHP Dependencies
├── db.sql                           # Database Schema
├── package.json                     # Node.js Dependencies
├── README.md                        # This Documentation
└── tailwind.config.js               # Tailwind Configuration
```

---

## Database Schema

### Entity Relationship Diagram

```
┌──────────────────┐       ┌──────────────────┐
│      users       │       │    products      │
├──────────────────┤       ├──────────────────┤
│ id (PK)          │       │ id (PK)          │
│ username         │       │ user_id (FK)     │──┐
│ email            │       │ name             │  │
│ password         │       │ description      │  │
│ full_name        │       │ price            │  │
│ phone            │       │ category_id      │  │
│ avatar           │       │ image            │  │
│ created_at       │       │ status           │  │
│ updated_at       │       │ created_at       │  │
└──────────────────┘       │ updated_at       │  │
         │                 └──────────────────┘  │
         │                          │            │
         └──────────────────────────┼────────────┘
                                    │
                         ┌──────────────────┐
                         │   categories     │
                         ├──────────────────┤
                         │ id (PK)          │
                         │ name             │
                         │ slug             │
                         │ icon             │
                         └──────────────────┘
```

### Tables Description

| Table        | Description        | Records (Est.) |
| ------------ | ------------------ | -------------- |
| `users`      | User accounts      | 100+           |
| `products`   | Product listings   | 500+           |
| `categories` | Product categories | 10-20          |

---

## Development Workflow

### Git Branch Strategy

```
main (production)
  │
  └── develop (staging)
        │
        ├── feature/auth-login
        ├── feature/product-listing
        └── feature/search-filter
```

### Commit Convention

```
<type>(<scope>): <description>

[optional body]

[optional footer]
```

**Types:**
| Type | Description |
|------|-------------|
| `feat` | New feature |
| `fix` | Bug fix |
| `docs` | Documentation |
| `style` | Formatting (no code change) |
| `refactor` | Code restructuring |
| `test` | Adding tests |
| `chore` | Maintenance |

**Examples:**

```bash
feat(auth): add login functionality
fix(product): resolve image upload issue
docs(readme): update installation guide
```

### Code Review Checklist

- [ ] Code follows PSR-12 standard
- [ ] No hardcoded values
- [ ] Proper error handling
- [ ] SQL injection prevention
- [ ] XSS prevention
- [ ] Responsive design tested
- [ ] Cross-browser tested

---

## Team Members

|  #  | Name           | Student ID | Role          | Responsibilities                      |
| :-: | -------------- | :--------: | ------------- | ------------------------------------- |
|  1  | [Thành viên 1] |   [MSSV]   | **Team Lead** | Project management, Backend, Database |
|  2  | [Thành viên 2] |   [MSSV]   | Developer     | Frontend, UI/UX Design                |
|  3  | [Thành viên 3] |   [MSSV]   | Developer     | Frontend, Integration, Testing        |

---

## Task Assignment

### Member 1 - Team Lead

| Task                                 |     Status     | Priority |
| ------------------------------------ | :------------: | :------: |
| Database schema design               |    ✅ Done     |    P0    |
| User authentication (Login/Register) |    ✅ Done     |    P0    |
| Product CRUD API                     | 🔄 In Progress |    P0    |
| Project management & code review     |   🔄 Ongoing   |    P0    |

### Member 2 - Frontend Developer

| Task                    |     Status     | Priority |
| ----------------------- | :------------: | :------: |
| UI/UX Design (Figma)    |    ✅ Done     |    P0    |
| Homepage implementation |    ✅ Done     |    P0    |
| Product detail page     | 🔄 In Progress |    P1    |
| Responsive design       |   ⏳ Pending   |    P1    |

### Member 3 - Frontend Developer

| Task                          |     Status     | Priority |
| ----------------------------- | :------------: | :------: |
| Header & Footer components    |    ✅ Done     |    P0    |
| Search & Filter functionality | 🔄 In Progress |    P1    |
| Frontend-Backend integration  |   ⏳ Pending   |    P1    |
| Testing & bug fixes           |   ⏳ Pending   |    P1    |

---

## Project Timeline

### Gantt Chart

```
Week    1    2    3    4    5    6    7    8
        |----|----|----|----|----|----|----|----|
Phase 1 ████████                                   Planning & Design
Phase 2           ████████                         Core Development
Phase 3                     ████████               Feature Implementation
Phase 4                               ████████     Testing & Deployment
```

### Milestones

| Milestone              | Due Date |     Status     | Deliverables                |
| ---------------------- | :------: | :------------: | --------------------------- |
| **M1**: Project Setup  |  Week 2  |  ✅ Complete   | Repo, DB schema, Basic UI   |
| **M2**: Auth Module    |  Week 4  |  ✅ Complete   | Login, Register, Session    |
| **M3**: Product Module |  Week 6  | 🔄 In Progress | CRUD, Search, Filter        |
| **M4**: Final Release  |  Week 8  |   ⏳ Pending   | Full testing, Documentation |

### Sprint Progress

**Current Sprint: Sprint 3 (Week 5-6)**

```
Progress: ████████░░░░░░░░░░░░ 40%

Completed: 4/10 tasks
In Progress: 3/10 tasks
Pending: 3/10 tasks
```

---

## Changelog

### [1.0.0] - 2025-XX-XX (Planned)

- Initial release
- User authentication
- Product management
- Search functionality

### [0.2.0] - 2025-12-28

#### Added

- Header & Footer components
- Login page UI
- Register page UI
- Tailwind CSS integration

#### Changed

- Updated project structure
- Improved responsive design

### [0.1.0] - 2025-12-15

#### Added

- Initial project setup
- Database schema
- Basic routing system
- MVC architecture

---

<br>

<p align="center">
  <img src="public/images/UniMarketHead.svg" alt="UniMarket" width="50">
</p>

<p align="center">
  <strong>UniMarket</strong><br>
  <sub>Đồ Cũ, Vẫn CHẤT</sub>
</p>

<p align="center">
  <sub>
    📅 Last Updated: December 28, 2025<br>
    🔒 Confidential - For Internal Use Only
  </sub>
</p>
