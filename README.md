# Internal Asset Exchange Platform


## Documentation
- Full specification: `cahier_des_charges.pdf` (in project root)
- UML diagrams: `docs/uml/`

## Configuration
Copy `config/database.example.php` to `config/database.php` and adjust credentials if needed (defaults work for standard XAMPP setups).

A multi-tenant internal web platform that lets companies manage and transfer unused equipment between departments — reducing waste and avoiding duplicate purchases.

Built with **PHP (custom MVC)**, **MySQL**, and **Bootstrap 5**.

---

## Features

- Multi-company workspaces with unique company codes (full data isolation)
- Secure authentication (email + password + company code), password hashing
- Role-based access: **Team Leader** and **Team Member**
- Asset management: add (with image upload), view, search/filter, delete
- Transfer workflow: request → approve/reject → history + savings tracking
- Member management and self-service password change
- Dashboard with statistics
- Email + popup notifications for key actions

---

## Requirements

- **XAMPP** (or any Apache + PHP 8+ + MySQL stack) — [download here](https://www.apachefriends.org/)
- A web browser

That's the only requirement — no Composer or external dependencies needed.

---

## Setup Instructions

### 1. Get the project files

Download/extract the project folder. Place it inside your XAMPP `htdocs` directory, for example:

```
C:\xampp\htdocs\internal-asset-exchange\
```

### 2. Start Apache and MySQL

Open the **XAMPP Control Panel** and click **Start** next to both **Apache** and **MySQL**.

### 3. Create the database

1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click the **Import** tab.
3. Click **Choose File** and select `sql/database.sql` from the project folder.
4. Click **Go**.

This will automatically create the `asset_exchange` database with all 4 required tables (`companies`, `users`, `assets`, `transfer_log`) and their relationships.

> No need to create the database manually first — the SQL file includes `CREATE DATABASE IF NOT EXISTS asset_exchange;`.

### 4. Configure the database connection

Open `config/database.php` and check the values match your local MySQL setup (XAMPP defaults shown below — usually no changes needed):

```php
<?php
return [
    'host' => 'localhost',
    'dbname' => 'asset_exchange',
    'user' => 'root',
    'pass' => '',
    'charset' => 'utf8mb4'
];
```

### 5. Set the BASE_URL

Open `public/index.php` and find this line near the top:

```php
define('BASE_URL', '/internal-asset-exchange');
```

Make sure the value matches the **name of your project folder** inside `htdocs`. For example, if your folder is `C:\xampp\htdocs\my-project`, set:

```php
define('BASE_URL', '/my-project');
```

### 6. Make the uploads folder writable

Confirm the folder `public/uploads/assets/` exists. On Windows/XAMPP this works out of the box. On Linux/Mac, run:

```bash
chmod -R 775 public/uploads/assets/
```

---

## Running the Project

Open your browser and go to:

```
http://localhost/internal-asset-exchange/
```

(replace `internal-asset-exchange` with your actual folder name, matching `BASE_URL`)

You should land on the **Login page**.

---

## First Use — Quick Walkthrough

1. **Create a company**
   - Click **"Create one"** on the login page.
   - Fill in: company name, your name, email, password, department.
   - Submit → you'll be redirected to the dashboard as **Team Leader**, and a popup will show your unique **Company Code** — save it, you'll need it to log in.

2. **Log in**
   - Use your email, password, and the company code shown earlier.

3. **Add an asset**
   - Click **"Add Asset"** in the navbar.
   - Fill in name, category, value, condition, description, and optionally upload an image.
   - Submit → the asset appears under **"View Assets"**.

4. **Request a transfer** (as a member from another department)
   - Open an asset's details page and click **"Request Transfer"**.

5. **Approve/Reject** (as Team Leader)
   - Go to **"Pending Approvals"** to review and approve or reject the request.

6. **Check history & savings**
   - Go to **"All Transfers"** to see completed transfers and total savings.

---

## Testing Multi-Tenant Isolation

Create a **second company** (different email, new "Create Company" form) and confirm:
- It gets its own unique company code
- Assets/users from Company A are **not visible** to Company B

---

## Project Structure

```
internal-asset-exchange/
├── config/        -> database.php (DB connection settings)
├── controllers/   -> AuthController, AssetController, TransferController, ...
├── models/        -> Company, User, Asset, TransferLog
├── views/         -> auth/, assets/, transfers/, members/, layouts/, dashboard.php
├── public/        -> index.php (entry point), uploads/, css/, js/
├── core/          -> Router, Controller, Model, Database
├── helpers/       -> SessionHelper, AuthHelper, SecurityHelper
├── sql/           -> database.sql (database creation script)
└── .htaccess      -> routes all requests to public/index.php
```

---

## Troubleshooting

**Blank page / "Class not found" error**
Make sure Apache's `mod_rewrite` is enabled and `AllowOverride All` is set for `htdocs` in `httpd.conf`, then restart Apache.

**404 Not Found on every page**
Check that `BASE_URL` in `public/index.php` exactly matches your project folder name (case-sensitive).

**Images not showing**
Confirm `public/uploads/assets/` exists and contains the uploaded image files, and that image `<img>` tags use `BASE_URL . '/public/...'`.

**"SecurityHelper not found" or similar class errors**
Make sure `public/index.php` includes all required files at the top:
```php
require __DIR__ . '/../helpers/SessionHelper.php';
require __DIR__ . '/../helpers/AuthHelper.php';
require __DIR__ . '/../helpers/SecurityHelper.php';
require __DIR__ . '/../core/Database.php';
require __DIR__ . '/../core/Router.php';
require __DIR__ . '/../core/Controller.php';
require __DIR__ . '/../core/Model.php';
```

---

## License

All Rights Reserved. This project is proprietary and was developed for personal/educational purposes. No part of this code may be copied, distributed, modified, or used without explicit permission from the author.
