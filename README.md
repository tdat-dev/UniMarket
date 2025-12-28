<p align="center">
  <img src="public/images/UniMarketHead.svg" alt="UniMarket Logo" width="120" height="120">
</p>

<h1 align="center">UniMarket</h1>

<p align="center">
  <strong>🎓 Sàn Thương Mại Điện Tử Dành Cho Sinh Viên</strong>
</p>

<p align="center">
  <em>"Đồ Cũ, Vẫn CHẤT"</em>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/version-1.0.0-blue.svg" alt="Version">
  <img src="https://img.shields.io/badge/php-%3E%3D8.0-777BB4.svg?logo=php&logoColor=white" alt="PHP Version">
  <img src="https://img.shields.io/badge/status-in%20development-yellow.svg" alt="Status">
</p>

---

## 📋 Mục Lục

- [Giới Thiệu](#giới-thiệu)
- [Tính Năng](#tính-năng)
- [Công Nghệ Sử Dụng](#công-nghệ-sử-dụng)
- [Cài Đặt](#cài-đặt)
- [Cấu Trúc Dự Án](#cấu-trúc-dự-án)
- [Thành Viên Nhóm](#thành-viên-nhóm)
- [Phân Công Công Việc](#phân-công-công-việc)

---

## 🎯 Giới Thiệu

**UniMarket** là dự án môn học - nền tảng thương mại điện tử được thiết kế dành cho sinh viên Việt Nam. Website giúp sinh viên có thể mua bán, trao đổi đồ dùng học tập, thiết bị điện tử, sách vở và nhiều mặt hàng khác với giá cả phải chăng.

### 🌟 Điểm Nổi Bật

- **💰 Tiết Kiệm Chi Phí**: Mua đồ cũ chất lượng với giá rẻ
- **🎓 Dành Riêng Cho Sinh Viên**: Giao diện thân thiện, dễ sử dụng
- **🚀 Giao Dịch Nhanh Chóng**: Kết nối trực tiếp người mua và người bán
- **♻️ Bảo Vệ Môi Trường**: Tái sử dụng đồ dùng, giảm rác thải

---

## ✨ Tính Năng

### 👤 Người Dùng

- [x] Đăng ký / Đăng nhập
- [x] Quản lý hồ sơ cá nhân
- [ ] Đăng nhập bằng Google OAuth

### 🛍️ Mua Sắm

- [x] Tìm kiếm sản phẩm
- [x] Lọc theo danh mục, giá
- [ ] Giỏ hàng
- [ ] Hệ thống gợi ý sản phẩm

### 📦 Bán Hàng

- [x] Đăng bán sản phẩm
- [ ] Quản lý sản phẩm đã đăng
- [ ] Theo dõi đơn hàng

---

## 🛠️ Công Nghệ Sử Dụng

### Backend

| Công nghệ | Phiên bản | Mô tả                    |
| --------- | --------- | ------------------------ |
| PHP       | 8.0+      | Ngôn ngữ lập trình chính |
| MySQL     | 8.0+      | Cơ sở dữ liệu            |
| Composer  | 2.x       | Quản lý dependencies     |

### Frontend

| Công nghệ    | Phiên bản | Mô tả              |
| ------------ | --------- | ------------------ |
| Tailwind CSS | 3.x       | CSS Framework      |
| JavaScript   | ES6+      | Ngôn ngữ scripting |
| Font Awesome | 6.x       | Icon library       |

### Môi Trường Phát Triển

- **Laragon** - Local development environment
- **VS Code** - Code editor
- **Git** - Version control

---

## 🚀 Cài Đặt

### Yêu Cầu

- PHP >= 8.0
- MySQL >= 8.0
- Composer >= 2.0
- Node.js >= 16.0

### Bước 1: Clone Repository

```bash
git clone https://github.com/your-username/UniMarket.git
cd UniMarket
```

### Bước 2: Cài Đặt Dependencies

```bash
composer install
npm install
```

### Bước 3: Cấu Hình Database

1. Tạo database mới tên `unimarket`
2. Import file `db.sql` vào database
3. Copy file `.env.example` thành `.env` và cấu hình:

```env
DB_HOST=127.0.0.1
DB_DATABASE=unimarket
DB_USERNAME=root
DB_PASSWORD=
```

### Bước 4: Build CSS & Chạy

```bash
npm run dev
```

Truy cập: `http://unimarket.test` (Laragon) hoặc `http://localhost:8000`

---

## 📁 Cấu Trúc Dự Án

```
UniMarket/
├── app/                    # Application core
│   ├── Controllers/        # Xử lý request
│   ├── Core/               # Framework core
│   ├── Models/             # Data models
│   └── Services/           # Business logic
├── config/                 # File cấu hình
├── public/                 # Public assets (web root)
│   ├── css/
│   ├── images/
│   ├── js/
│   └── index.php
├── resources/views/        # Giao diện
│   ├── auth/               # Đăng nhập, đăng ký
│   └── partials/           # Header, footer
├── routes/web.php          # Định nghĩa routes
├── db.sql                  # Database schema
└── tailwind.config.js      # Cấu hình Tailwind
```

---

## 👥 Thành Viên Nhóm

| STT | Họ và Tên          | MSSV   | Vai trò     |
| :-: | ------------------ | ------ | ----------- |
|  1  | [Tên thành viên 1] | [MSSV] | Nhóm trưởng |
|  2  | [Tên thành viên 2] | [MSSV] | Thành viên  |
|  3  | [Tên thành viên 3] | [MSSV] | Thành viên  |

---

## 📝 Phân Công Công Việc

### Thành viên 1 - [Tên] (Nhóm trưởng)

- Thiết kế cơ sở dữ liệu
- Xây dựng backend API
- Chức năng đăng nhập/đăng ký
- Quản lý dự án

### Thành viên 2 - [Tên]

- Thiết kế giao diện UI/UX
- Xây dựng trang chủ
- Trang chi tiết sản phẩm
- Responsive design

### Thành viên 3 - [Tên]

- Xây dựng Header/Footer
- Trang tìm kiếm sản phẩm
- Tích hợp frontend-backend
- Testing & debug

---

## 📅 Tiến Độ Dự Án

| Tuần | Công việc                        |    Trạng thái     |
| :--: | -------------------------------- | :---------------: |
| 1-2  | Phân tích yêu cầu, thiết kế CSDL |   ✅ Hoàn thành   |
| 3-4  | Xây dựng giao diện cơ bản        |   ✅ Hoàn thành   |
| 5-6  | Phát triển chức năng chính       | 🔄 Đang thực hiện |
| 7-8  | Testing & hoàn thiện             |  ⏳ Chưa bắt đầu  |

---

<p align="center">
  <img src="public/images/UniMarketHead.svg" alt="UniMarket" width="60">
</p>

<p align="center">
  <strong>UniMarket</strong> - Đồ Cũ, Vẫn CHẤT
</p>

<p align="center">
  <em>Dự án môn học - 2025</em>
</p>
