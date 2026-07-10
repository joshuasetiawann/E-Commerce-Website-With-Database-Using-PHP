<div align="center">

<img src="images/main-logo.png" alt="Company logo" width="150">

# Company — E-Commerce Website

**A complete, security-conscious e-commerce web application built with PHP & MySQL.**

A customer-facing storefront (catalog, search, product details, and a session-based shopping cart) paired with a secured admin panel for managing categories and products — backed by a normalized MySQL schema and written entirely with prepared statements.

<p>
  <img src="https://img.shields.io/badge/PHP-8.0%2B-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP 8.0+">
  <img src="https://img.shields.io/badge/MySQL-%2F%20MariaDB-4479A1?style=flat-square&logo=mysql&logoColor=white" alt="MySQL / MariaDB">
  <img src="https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat-square&logo=bootstrap&logoColor=white" alt="Bootstrap 5">
  <img src="https://img.shields.io/badge/CKEditor-4-0287D0?style=flat-square&logo=ckeditor4&logoColor=white" alt="CKEditor 4">
  <img src="https://img.shields.io/badge/license-MIT-22c55e?style=flat-square" alt="MIT License">
</p>

[Features](#-features) · [Tech Stack](#%EF%B8%8F-tech-stack) · [Architecture](#-architecture--request-flow) · [Database](#%EF%B8%8F-database-schema) · [Getting Started](#-getting-started) · [Usage](#-usage-guide) · [Security](#-security) · [Roadmap](#-roadmap)

</div>

---

## 📑 Table of Contents

- [Overview](#-overview)
- [Features](#-features)
- [Tech Stack](#%EF%B8%8F-tech-stack)
- [Architecture & Request Flow](#-architecture--request-flow)
- [Project Structure](#-project-structure)
- [Database Schema](#%EF%B8%8F-database-schema)
- [Getting Started](#-getting-started)
- [Configuration](#%EF%B8%8F-configuration)
- [Usage Guide](#-usage-guide)
- [Security](#-security)
- [Roadmap](#-roadmap)
- [Contributing](#-contributing)
- [License](#-license)

---

## 📖 Overview

**Company** is a self-contained e-commerce application demonstrating a clean, no-framework PHP architecture. It is split into two logical areas:

- A **public storefront** where visitors browse the catalog, search and filter products, view product details, and build a shopping cart held in their session.
- A **protected admin panel** where authenticated staff manage the product catalog and categories, including image uploads and rich-text product descriptions.

The project deliberately uses **plain PHP with `mysqli` prepared statements** (no ORM, no framework) so the data flow stays transparent and easy to learn from — while still applying production-minded practices like bcrypt password hashing, an authentication guard, output escaping, and a normalized schema with foreign keys.

> 💡 The codebase ships with seed data (a default admin account and example categories) so you can run it end-to-end immediately after importing the SQL file.

---

## ✨ Features

### 🛍️ Storefront (public)

| Page | What it does |
| --- | --- |
| **Landing page** (`index.php`) | Themed homepage with hero banner, product highlights, and a responsive Bootstrap layout. |
| **Catalog** (`products.php`) | Lists active products with **live keyword search** and **category filtering**, powered by prepared `LIKE` queries. |
| **Product detail** (`product-detail.php`) | Full product view with image, formatted price, rich-text description, and a **"Contact via WhatsApp"** button that deep-links to the store's number. |
| **Shopping cart** (`cart.php`) | **Session-based cart** with add, update quantity, remove, and checkout. Includes a slide-out cart panel, live item count badge, and running total in Rupiah. |

### 🔐 Admin panel (login required)

| Capability | Files |
| --- | --- |
| **Authentication** | Sign in **and** self sign-up, with bcrypt-hashed passwords (`login.php`, `logout.php`). |
| **Auth guard** | Every admin page includes `auth.php`, which redirects unauthenticated visitors to the login screen. |
| **Dashboard** | Landing screen after login (`dashboard.php`). |
| **Category management** | List, add, edit, and delete categories (`manage-categories.php`, `add-category.php`, `edit-category.php`). |
| **Product management** | List, add, edit, and delete products with **image upload** and a **CKEditor rich-text** description (`manage-products.php`, `add-product.php`, `edit-product.php`). |
| **Safe delete** | `delete.php` removes the record **and** the associated image file from disk; category deletes cascade to their products at the database level. |

---

## 🛠️ Tech Stack

| Layer | Technology | Notes |
| --- | --- | --- |
| **Backend** | PHP 8 (`mysqli`) | Procedural style; **prepared statements** everywhere. |
| **Database** | MySQL / MariaDB | InnoDB, `utf8mb4`, foreign keys with `ON DELETE CASCADE`. |
| **Frontend** | HTML5, CSS3, Bootstrap 5, jQuery | Responsive layouts and components. |
| **Rich text** | CKEditor 4 (`4.14.0`) | Used for product descriptions in the admin panel. |
| **Icons / extras** | Bootstrap Icons, Swiper, Google Fonts | Storefront UI polish. |
| **Sessions** | PHP native sessions | Used for both the admin login state and the shopping cart. |

---

## 🏗 Architecture & Request Flow

```
                          ┌──────────────────────────────────────┐
                          │              Visitor / Admin           │
                          └───────────────────┬────────────────────┘
                                              │ HTTP
                  ┌───────────────────────────┴───────────────────────────┐
                  ▼                                                         ▼
      ┌───────────────────────┐                            ┌───────────────────────────┐
      │   STOREFRONT (public)  │                            │     ADMIN PANEL (guarded)   │
      │                        │                            │                             │
      │  index.php             │                            │  login.php → session login  │
      │  products.php  (search)│                            │  auth.php  (guard include)  │
      │  product-detail.php    │                            │  dashboard.php              │
      │  cart.php  (session)   │                            │  manage / add / edit / del  │
      └───────────┬────────────┘                            └──────────────┬──────────────┘
                  │                                                         │
                  └─────────────────────────┬───────────────────────────────┘
                                            ▼
                              ┌──────────────────────────┐
                              │   db.php (mysqli + e())   │   ← prepared statements
                              └─────────────┬────────────┘
                                            ▼
                              ┌───────────────────────────┐
                              │   MySQL  ·  company_shop   │
                              │  tb_admin  · tb_category   │
                              │  tb_product · tb_cart_items│
                              └───────────────────────────┘
```

**How it fits together**

- `db.php` is the single entry point for the database. It opens the `mysqli` connection (with environment-variable overrides), sets `utf8mb4`, and exposes the `e()` helper for HTML-escaping output.
- Every admin page starts with `require 'auth.php';`, so access control is enforced consistently in one place.
- The storefront cart lives in `$_SESSION['cart']` as a `product_id => quantity` map — no login required to shop.

---

## 📁 Project Structure

```
.
├── index.php              # Storefront landing page
├── products.php           # Product catalog (search + category filter)
├── product-detail.php     # Product detail page (+ WhatsApp contact)
├── cart.php               # Session-based shopping cart
│
├── login.php              # Admin sign in / sign up
├── logout.php             # Destroys the session
├── auth.php               # Admin auth guard (included by every admin page)
├── dashboard.php          # Admin dashboard
│
├── manage-categories.php  # Category list
├── add-category.php       # Add category
├── edit-category.php      # Edit category
│
├── manage-products.php    # Product list
├── add-product.php        # Add product (image upload + CKEditor)
├── edit-product.php       # Edit product
├── delete.php             # Delete handler (category / product + image cleanup)
│
├── db.php                 # Database connection + e() escaping helper
├── company_shop.sql       # Schema + seed data (admin account, categories)
│
├── css/                   # Stylesheets (Bootstrap, theme, cart, login)
├── js/                    # Scripts (Bootstrap bundle, jQuery, cart, login)
├── images/                # Static theme images (banner, logo, payment icons)
├── img/                   # Misc icons (WhatsApp, category)
├── products/              # Uploaded product images (written at runtime)
├── assets/                # Favicon, etc.
│
├── .gitignore
├── LICENSE                # MIT
└── README.md
```

---

## 🗄️ Database Schema

Database name: **`company_shop`** (InnoDB · `utf8mb4`). Created and seeded by `company_shop.sql`.

```
┌────────────────────┐         ┌────────────────────┐
│     tb_admin       │         │    tb_category     │
├────────────────────┤         ├────────────────────┤
│ admin_id      (PK) │         │ category_id   (PK) │
│ admin_name         │         │ category_name      │
│ username   (unique)│         └─────────┬──────────┘
│ password  (bcrypt) │                   │ 1
│ admin_telp         │                   │
│ admin_email        │                   │ N      (ON DELETE CASCADE)
│ admin_address      │         ┌─────────┴──────────┐
└────────────────────┘         │     tb_product     │
                               ├────────────────────┤
┌────────────────────┐         │ product_id    (PK) │
│   tb_cart_items    │   N      │ category_id   (FK) │
├────────────────────┤◄────────┤ product_name       │
│ cart_item_id  (PK) │         │ product_price      │
│ user_id            │         │ product_description│
│ product_id    (FK) │         │ product_image      │
│ quantity           │         │ product_status     │
│ date_added         │         │ date_created       │
│ last_updated       │         └────────────────────┘
└────────────────────┘
```

| Table | Purpose |
| --- | --- |
| `tb_admin` | Admin accounts. `username` is unique; `password` stores a bcrypt hash. |
| `tb_category` | Product categories. |
| `tb_product` | Products, linked to a category via `category_id` (FK, **cascade** on delete/update). `product_status` toggles visibility on the storefront. |
| `tb_cart_items` | A persistent-cart table included in the schema for future database-backed carts. *(The current cart is session-based — see [Roadmap](#-roadmap).)* |

**Seed data** (loaded automatically):
- Default admin → `admin` / `admin123` (bcrypt-hashed in the SQL).
- Example categories → *Electronics*, *Accessories*, *Fashion*.

---

## 🚀 Getting Started

### Prerequisites

- **PHP 8.0+**
- **MySQL** or **MariaDB**
- A local server stack such as **XAMPP**, **Laragon**, or **MAMP** (Apache + PHP + MySQL)

### Installation

**1. Clone the repository into your web root**

```bash
# e.g. C:\xampp\htdocs on Windows, or /Applications/XAMPP/htdocs on macOS
git clone https://github.com/joshuasetiawann/e-commerce-website-with-database.git
```

**2. Import the database**

Via the command line:

```bash
mysql -u root -p < company_shop.sql
```

…or import `company_shop.sql` through **phpMyAdmin**. Either way it creates the `company_shop` database with all tables, the default admin account, and example categories.

**3. Configure the database connection** *(optional)*

The defaults in `db.php` work out of the box for a standard XAMPP setup (host `localhost`, user `root`, empty password). To override them **without editing code**, set environment variables — see [Configuration](#%EF%B8%8F-configuration).

**4. Start the stack and open the app**

Start Apache + MySQL, then visit:

| Area | URL |
| --- | --- |
| 🛍️ Storefront | `http://localhost/E-Commerce-Website-With-Database/index.php` |
| 🔐 Admin login | `http://localhost/E-Commerce-Website-With-Database/login.php` |

> Adjust the folder name in the URL if you cloned into a different directory.

### Default Admin Credentials

| Username | Password   |
| -------- | ---------- |
| `admin`  | `admin123` |

> ⚠️ **Change this password immediately after your first login.**

---

## ⚙️ Configuration

`db.php` reads its credentials from environment variables and falls back to sensible local defaults, so you never have to commit secrets:

| Variable  | Default        | Description |
| --------- | -------------- | --- |
| `DB_HOST` | `localhost`    | Database host. |
| `DB_USER` | `root`         | Database user. |
| `DB_PASS` | *(empty)*      | Database password. |
| `DB_NAME` | `company_shop` | Database name. |

The connection uses the `utf8mb4` charset and procedural error handling, and `db.php` also defines the `e()` helper used throughout the views to escape output safely.

---

## 📘 Usage Guide

### As a shopper

1. Open the storefront and browse the catalog on **Products**.
2. Use the **search box** and **category filter** to narrow results.
3. Click a product to open its **detail page**, then either **Add to Cart** or **Contact via WhatsApp**.
4. Open the **slide-out cart** to adjust quantities, remove items, see the running total, and **Checkout**.

### As an admin

1. Go to `login.php` and sign in (or create a new account via **Sign up**).
2. From the **Dashboard**, use the nav to manage **Categories** and **Products**.
3. When adding a product, choose a category, set the price, upload an image (`jpg`, `jpeg`, `png`, `gif`), and write the description in the **CKEditor** rich-text field.
4. Set a product's status to **Inactive** to hide it from the storefront without deleting it. Deleting a product also removes its uploaded image file.

---

## 🔒 Security

This project was built with the following protections in place:

- **SQL injection protection** — all database access uses **prepared statements** with bound parameters, including dynamically built `IN (...)` clauses in the cart.
- **Password hashing** — passwords are stored with `password_hash()` (bcrypt) and verified with `password_verify()`. No plaintext, no MD5. The seeded admin hash is a real bcrypt hash.
- **Access control** — admin pages include `auth.php`, which redirects unauthenticated visitors to the login screen and enforces the session check in one shared place.
- **Output escaping** — user-facing values are escaped with the `e()` helper (`htmlspecialchars`, `ENT_QUOTES`) before rendering, mitigating reflected XSS in search terms, product names, and prices.
- **File-upload validation** — product image uploads are restricted to a `jpg / jpeg / png / gif` extension allowlist, and uploaded files are stored under `products/` with a generated, timestamped filename.
- **No committed secrets** — credentials are read from environment variables, and `.gitignore` excludes `.env` files, logs, and database dumps.

> ℹ️ **Note on rich text:** product descriptions are authored by trusted admins via CKEditor and are intentionally rendered as HTML on the detail page. For multi-author or untrusted-admin scenarios, sanitize this HTML server-side (e.g. with HTML Purifier) — tracked in the [Roadmap](#-roadmap).

---

## 🗺 Roadmap

Ideas and known follow-ups to take this from a solid demo toward production:

- [ ] **Persist orders** — checkout currently clears the cart; record orders in dedicated `orders` / `order_items` tables.
- [ ] **Database-backed cart** — wire the existing `tb_cart_items` table to logged-in users so carts survive across devices.
- [ ] **HTML sanitization** for admin-authored product descriptions (HTML Purifier).
- [ ] **CSRF tokens** on state-changing forms (login, cart actions, admin CRUD).
- [ ] **Pagination** on the catalog and admin product list.
- [ ] **Image hardening** — validate MIME type (not just extension) and enforce a max upload size.
- [ ] **Role separation** between customers and administrators.
- [ ] **Replace inline `alert()` redirects** with proper flash messages.

---

## 🤝 Contributing

Contributions are welcome! To propose a change:

1. Fork the repository and create a feature branch (`git checkout -b feature/your-feature`).
2. Commit your changes with a clear message.
3. Open a pull request describing what changed and why.

For larger changes, please open an issue first to discuss the approach.

---

## 📄 License

This project is licensed under the **[MIT License](LICENSE)** — © 2025 Company.

You are free to use, modify, and distribute it; see the `LICENSE` file for the full text.

---

<div align="center">

⭐ **If this project helped you, consider giving it a star!**

</div>
