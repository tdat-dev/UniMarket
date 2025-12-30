<p align="center">
  <img src="public/images/LogoHeader.png" alt="UniMarket Logo" width="250">
</p>

<p align="center">
  <strong>Nền Tảng Thương Mại Điện Tử Dành Cho Sinh Viên</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/version-1.0.0-blue.svg" alt="Version">
  <img src="https://img.shields.io/badge/php-8.0+-777BB4.svg" alt="PHP">
  <img src="https://img.shields.io/badge/mysql-8.0+-4479A1.svg" alt="MySQL">
  <img src="https://img.shields.io/badge/tailwind-3.x-38B2AC.svg" alt="Tailwind">
</p>

---

## 📋 Mục lục

- [Giới thiệu](#-giới-thiệu)
- [Yêu cầu hệ thống](#-yêu-cầu-hệ-thống)
- [Cài đặt nhanh](#-cài-đặt-nhanh)
- [Cấu trúc dự án](#-cấu-trúc-dự-án)
- [Database & Migrations](#-database--migrations)
- [Quy trình làm việc](#-quy-trình-làm-việc)
- [Thành viên nhóm](#-thành-viên-nhóm)

---

## 🎯 Giới thiệu

**UniMarket** là nền tảng mua bán đồ cũ dành cho sinh viên. Cho phép đăng bán, tìm kiếm và mua sản phẩm với giá sinh viên.

### Tính năng chính

| Tính năng               | Mô tả                         | Trạng thái |
| ----------------------- | ----------------------------- | :--------: |
| Đăng ký/Đăng nhập       | Xác thực người dùng           |     ✅     |
| Đăng bán sản phẩm       | Upload ảnh, nhập thông tin    |     ✅     |
| Tìm kiếm sản phẩm       | Tìm kiếm theo tên             |     ✅     |
| Gợi ý tìm kiếm hàng đầu | Tracking keyword phổ biến     |     ✅     |
| Phân trang              | Phân trang danh sách sản phẩm |     ✅     |

---

## 💻 Yêu cầu hệ thống

| Thành phần | Yêu cầu     |
| ---------- | ----------- |
| PHP        | >= 8.0      |
| MySQL      | >= 8.0      |
| Composer   | >= 2.0      |
| Node.js    | >= 16.0     |
| Laragon    | Khuyến nghị |

---

## 🚀 Cài đặt nhanh

### 1. Clone project

```bash
git clone <repository-url>
cd UniMarket
```

### 2. Cài dependencies

```bash
composer install
npm install
```

### 3. Cấu hình database

Copy file `.env.example` thành `.env` và sửa thông tin database:

```bash
cp .env.example .env
```

Sửa file `.env`:

```env
DB_HOST=127.0.0.1
DB_DATABASE=unimarket
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Tạo database và chạy migrations

```bash
# Tạo database trong MySQL
CREATE DATABASE unimarket CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Chạy migrations (tự động tạo các bảng)
php database/migrate.php
```

### 5. Build CSS (Tailwind)

```bash
npm run dev    # Development (watch mode)
npm run build  # Production
```

### 6. Chạy project

- **Laragon**: Truy cập `http://unimarket.test`
- **PHP Built-in**: `php -S localhost:8000 -t public`

---

## 📁 Cấu trúc dự án

```
UniMarket/
│
├── .env                         # Biến môi trường (DB config)
├── .env.example                 # Template cho .env
├── .gitignore                   # Ignore rules cho Git
├── composer.json                # PHP dependencies
├── package.json                 # Node.js dependencies
├── tailwind.config.js           # Cấu hình Tailwind CSS
├── db.sql                       # Full database schema (backup)
├── post.php                     # Test file
├── ARCHITECTURE.md              # Tài liệu kiến trúc
├── README.md                    # Tài liệu này
│
├── .github/                     # GitHub workflows
│
├── app/                         # Source code chính
│   ├── Controllers/             # Xử lý request
│   │   ├── AuthController.php       # Đăng nhập/Đăng ký
│   │   ├── BaseController.php       # Base class
│   │   ├── HomeController.php       # Trang chủ
│   │   ├── ProductController.php    # CRUD sản phẩm
│   │   └── SearchController.php     # Tìm kiếm
│   │
│   ├── Core/                    # Core framework
│   │   ├── App.php                  # Bootstrap
│   │   ├── Database.php             # Database connection
│   │   └── Router.php               # Routing
│   │
│   ├── Models/                  # Tương tác database
│   │   ├── BaseModel.php            # Base class
│   │   ├── Product.php              # Model sản phẩm
│   │   ├── SearchKeyword.php        # Model từ khóa tìm kiếm
│   │   └── User.php                 # Model người dùng
│   │
│   └── Services/                # Business logic
│       └── RecommendationService.php
│
├── config/                      # Cấu hình
│   ├── app.php                  # Cấu hình app
│   └── database.php             # Cấu hình database
│
├── database/                    # Database migrations
│   ├── migrate.php              # Script chạy migrations
│   └── migrations/              # Các file migration
│       ├── 001_create_base_tables.sql
│       ├── 002_create_products_table.sql
│       ├── 003_create_orders_tables.sql
│       ├── 004_create_social_tables.sql
│       ├── 005_create_system_tables.sql
│       ├── 006_create_search_keywords.sql
│       └── 007_add_quantity_if_missing.sql
│
├── public/                      # Web root (entry point)
│   ├── index.php                # Entry point
│   ├── css/                     # Compiled CSS
│   ├── js/                      # JavaScript
│   ├── images/                  # Hình ảnh
│   └── uploads/                 # User uploads
│
├── resources/                   # Resources
│   ├── css/                     # Tailwind source
│   ├── lang/                    # Ngôn ngữ
│   └── views/                   # Giao diện
│       ├── auth/                    # Login, Register
│       ├── home/                    # Trang chủ
│       ├── layouts/                 # Layouts
│       ├── partials/                # Header, Footer, Components
│       │   ├── head.php
│       │   ├── header.php
│       │   ├── footer.php
│       │   └── product_card.php
│       ├── products/                # Danh sách SP, Chi tiết
│       └── search/                  # Kết quả tìm kiếm
│
├── routes/                      # Routes
│   └── web.php                  # Định nghĩa routes
│
├── vendor/                      # Composer packages
└── node_modules/                # NPM packages
```

---

## 🗄️ Database & Migrations

### Chạy migrations

```bash
php database/migrate.php
```

### Danh sách migrations

| File                              | Mô tả                                     |
| --------------------------------- | ----------------------------------------- |
| `001_create_base_tables.sql`      | Bảng majors, users, categories            |
| `002_create_products_table.sql`   | Bảng products                             |
| `003_create_orders_tables.sql`    | Bảng orders, order_details                |
| `004_create_social_tables.sql`    | Bảng messages, reviews, favorites         |
| `005_create_system_tables.sql`    | Bảng interactions, notifications, reports |
| `006_create_search_keywords.sql`  | Bảng search_keywords                      |
| `007_add_quantity_if_missing.sql` | Thêm cột quantity nếu thiếu               |

### Thêm migration mới

```bash
# Tạo file mới
database/migrations/008_ten_migration.sql

# Chạy migrate
php database/migrate.php
```

---

## 🔄 Quy trình làm việc

### Git Workflow

```
main ──── develop ──── feature/xxx
              ↑
          Pull Request
```

### Quy tắc commit

```bash
feat(scope): thêm tính năng mới
fix(scope): sửa lỗi
docs: cập nhật tài liệu
style: format code
refactor: tái cấu trúc code
```

**Ví dụ:**

```bash
git commit -m "feat(search): thêm gợi ý tìm kiếm"
git commit -m "fix(product): sửa lỗi phân trang"
```

### Quy trình tạo feature mới

1. **Tạo branch**

   ```bash
   git checkout develop
   git pull origin develop
   git checkout -b feature/ten-tinh-nang
   ```

2. **Code & commit**

   ```bash
   git add .
   git commit -m "feat(xxx): mô tả"
   ```

3. **Push & tạo Pull Request**

   ```bash
   git push origin feature/ten-tinh-nang
   ```

4. **Review & merge** vào `develop`

---

## 👥 Thành viên nhóm

|  #  | Họ tên |  MSSV  | Vai trò       | Công việc                      |
| :-: | ------ | :----: | ------------- | ------------------------------ |
|  1  | [Tên]  | [MSSV] | **Team Lead** | Backend, Database, Review code |
|  2  | [Tên]  | [MSSV] | Frontend      | UI/UX, Giao diện               |
|  3  | [Tên]  | [MSSV] | Fullstack     | Tích hợp, Testing              |

---

## 📞 Liên hệ

Nếu có vấn đề, liên hệ Team Lead hoặc tạo Issue trên repository.

---

<p align="center">
  <strong>UniMarket</strong> - Đồ cũ, vẫn CHẤT!<br>
  <sub>📅 Cập nhật: 30/12/2025</sub>
</p>
