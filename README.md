# Company — E-Commerce Website

A simple but complete e-commerce web application built with **PHP** and **MySQL**.
It ships with a customer-facing storefront (product catalog, search, product
details, and a session-based shopping cart) and a secured **admin panel** for
managing categories and products.

<p align="center">
  <img src="images/main-logo.png" alt="Company logo" width="160">
</p>

---

## ✨ Features

### Storefront (public)
- Responsive landing page (`index.php`)
- Product listing with search and category filter (`products.php`)
- Product detail page with WhatsApp contact button (`product-detail.php`)
- Session-based shopping cart: add, update quantity, remove, checkout (`cart.php`)

### Admin panel (login required)
- Secure authentication with hashed passwords (`login.php`, `logout.php`)
- Dashboard (`dashboard.php`)
- Category management — list, add, edit, delete
- Product management — list, add, edit, delete (with image upload + rich-text
  description via CKEditor)

---

## 🛠️ Tech Stack

| Layer     | Technology                          |
| --------- | ----------------------------------- |
| Backend   | PHP 8 (mysqli, prepared statements) |
| Database  | MySQL / MariaDB                     |
| Frontend  | HTML5, CSS3, Bootstrap, jQuery      |
| Editor    | CKEditor 4 (product descriptions)   |

---

## 📁 Project Structure

```
.
├── index.php             # Storefront landing page
├── products.php          # Product catalog (search/filter)
├── product-detail.php    # Product detail page
├── cart.php              # Shopping cart
│
├── login.php             # Admin sign in / sign up
├── logout.php            # Logout
├── auth.php              # Admin auth guard (included by admin pages)
├── dashboard.php         # Admin dashboard
│
├── manage-categories.php # Category list
├── add-category.php      # Add category
├── edit-category.php     # Edit category
│
├── manage-products.php   # Product list
├── add-product.php       # Add product
├── edit-product.php      # Edit product
├── delete.php            # Delete handler (category/product)
│
├── db.php                # Database connection + helpers
├── company_shop.sql      # Database schema + seed data
│
├── css/                  # Stylesheets
├── js/                   # Scripts
├── images/               # Static theme images
├── img/                  # Misc icons (WhatsApp, etc.)
├── products/             # Uploaded product images
└── assets/               # Favicon, etc.
```

---

## 🚀 Getting Started

### Prerequisites
- PHP 8.0 or newer
- MySQL or MariaDB
- A local server stack such as **XAMPP**, **Laragon**, or **MAMP**
  (Apache + PHP + MySQL)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/joshuasetiawann/e-commerce-website-with-database.git
   ```
   Place the folder inside your web root (e.g. `htdocs` for XAMPP).

2. **Import the database**

   Using the command line:
   ```bash
   mysql -u root -p < company_shop.sql
   ```
   Or import `company_shop.sql` through **phpMyAdmin**. This creates the
   `company_shop` database with all tables and a default admin account.

3. **Configure the database connection**

   The defaults in `db.php` work out of the box for a standard XAMPP setup
   (host `localhost`, user `root`, empty password). To override them without
   editing code, set environment variables:

   | Variable  | Default        |
   | --------- | -------------- |
   | `DB_HOST` | `localhost`    |
   | `DB_USER` | `root`         |
   | `DB_PASS` | *(empty)*      |
   | `DB_NAME` | `company_shop` |

4. **Run the app**

   Start Apache + MySQL, then open:
   - Storefront: `http://localhost/E-Commerce-Website-With-Database/index.php`
   - Admin login: `http://localhost/E-Commerce-Website-With-Database/login.php`

### Default Admin Credentials

| Username | Password   |
| -------- | ---------- |
| `admin`  | `admin123` |

> ⚠️ Change this password after your first login.

---

## 🔒 Security Notes

This project was hardened with the following practices:

- **SQL injection protection** — all database access uses prepared statements.
- **Password hashing** — passwords are stored with `password_hash()` (bcrypt)
  and verified with `password_verify()`; no plaintext or MD5.
- **Access control** — admin pages are protected by `auth.php`, which redirects
  unauthenticated visitors to the login screen.
- **Output escaping** — user-supplied values are escaped with `htmlspecialchars`
  before being rendered, mitigating XSS.
- **File-upload validation** — product image uploads are restricted to
  `jpg`, `jpeg`, `png`, and `gif`.

---

## 📄 License

This project is licensed under the [MIT License](LICENSE).
