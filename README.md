# Internal Asset Exchange Platform

A multi-tenant internal web platform that lets companies manage and transfer unused equipment between departments — reducing waste and avoiding duplicate purchases.

Built with **PHP (custom MVC)**, **MySQL**, **Bootstrap 5**, **PHPMailer**, and **Google Gemini AI**.

---

## Documentation
- UML diagrams: `docs/uml/`

---

## Features

- Multi-company workspaces with unique company codes (full data isolation)
- Secure authentication (email + password + company code), password hashing
- Role-based access: **Team Leader** and **Team Member**
- Asset management: add (with image upload), view, search/filter, delete (same-department guard)
- **AI-powered asset description generation** (Google Gemini) — auto-fills description from name + category
- **AI natural language search** — type "cheap chairs in IT" and filters apply automatically
- Transfer workflow: request → approve/reject → history + savings tracking (Leader-only)
- Member management (Leader adds members, auto-generated temporary password)
- Self-service password change
- Dashboard with statistics (available/transferred assets, total savings, top valued assets, pending approvals)
- Real email notifications via Gmail SMTP + PHPMailer
- Popup (JS alert) feedback for every user action
- Fully Dockerized — runs with one command, no setup needed

---

## ⚡ Quickstart with Docker (Recommended)

The easiest way — no XAMPP, no phpMyAdmin, no manual database setup needed.

### Requirements
- [Docker Desktop](https://www.docker.com/products/docker-desktop) installed and running (free)
- A web browser

### Steps

**1. Clone the repository $ Set up the database file:**
```bash
git clone https://github.com/mohammedzaha/internal-asset-exchange-pltform.git
cd internal-asset-exchange-pltform
```

```bash
cp config/database.example.php config/database.php
```

**2.  Set up AI features:**
```bash
cp config/ai.example.php config/ai.php
```
Open `config/ai.php` and paste your free Gemini API key from `https://aistudio.google.com/apikey`. Skip this step if you don't need AI features.

**3. Start the app:**
```bash
docker-compose up --build
```
Wait ~2 minutes for the first build. You'll see `mysqld: ready for connections` and Apache startup logs when ready.

**4. Open your browser:**
```
http://localhost:8080/internal-asset-exchange-platform/public/
```

**5. To stop:**
```bash
docker-compose down
```

> Database is created and imported automatically on first start — no manual setup needed.

---

## Alternative: Run with XAMPP (Local Development)

### Requirements
- [XAMPP](https://www.apachefriends.org/) (Apache + PHP 8+ + MySQL)
- A web browser

### Steps

**1. Place the project in XAMPP's htdocs:**
```
C:\xampp\htdocs\internal-asset-exchange-platform\
```

**2. Start Apache and MySQL** from the XAMPP Control Panel.

**3. Create the database:**
1. Go to `http://localhost/phpmyadmin`
2. Click **Import** → choose `sql/database.sql` → click **Go**

> The SQL file includes `CREATE DATABASE IF NOT EXISTS internal_asset_exchange_db;` — no manual database creation needed.

**4. Configure the database connection:**

Copy `config/database.example.php` to `config/database.php` (XAMPP defaults work as-is):
```php
<?php
return [
    'host'    => 'localhost',
    'dbname'  => 'internal_asset_exchange_db',
    'user'    => 'root',
    'pass'    => '',
    'charset' => 'utf8mb4'
];
```

**5. Set BASE_URL:**

Open `public/index.php` and confirm:
```php
define('BASE_URL', '/internal-asset-exchange-platform');
```
Change the value if your folder name is different.

**6. Set up email notifications (PHPMailer):**
1. Download [PHPMailer](https://github.com/PHPMailer/PHPMailer) → copy `PHPMailer.php`, `SMTP.php`, `Exception.php` from `src/` into `libraries/PHPMailer/`
2. Get a Gmail App Password at `https://myaccount.google.com/apppasswords`
3. Open `helpers/MailHelper.php` and set:
```php
$mail->Username = 'your-gmail@gmail.com';
$mail->Password = 'your-16-char-app-password';
$mail->setFrom('your-gmail@gmail.com', 'Asset Exchange Platform');
```

**7. (Optional) Set up AI features:**

Copy `config/ai.example.php` to `config/ai.php` and paste your Gemini API key.

**8. Open your browser:**
```
http://localhost/internal-asset-exchange-platform/
```

---

## First Use — Quick Walkthrough

1. **Create a company** — click "Create a company workspace", fill the form → you become Team Leader, a popup shows your unique Company Code, and a welcome email is sent. Save the code.

2. **Log in** — use your email, password, and company code.

3. **Add an asset** — click "Add Asset", fill in details, optionally upload an image. Use "✨ Generate with AI" to auto-generate the description.

4. **Add a team member** (Leader only) — click "Add Member", fill the form → a temporary password is generated, shown in a popup, and emailed to the new member.

5. **Request a transfer** — open an asset's details page, click "Request Transfer" → the Leader receives an email notification.

6. **Approve/Reject** (Leader only) — go to "Pending Approvals", review and decide → requester receives an email with the outcome.

7. **Check history & savings** (Leader only) — go to "All Transfers" to see completed transfers and total savings.

8. **Change password** — click "Change Password" in the navbar (current password required, min 6 characters).

9. **Smart search** — on the assets page, use the "Smart Search" box to search in plain English (e.g. "cheap laptops in IT department").

---

## Testing Multi-Tenant Isolation

Create a **second company** and confirm:
- It gets its own unique company code
- Assets/users/transfers from Company A are **not visible** to Company B

---

## Project Structure

```
internal-asset-exchange-platform/
├── Dockerfile                     -> Docker build instructions
├── docker-compose.yml             -> Orchestrates app + MySQL containers
├── .docker/
│   └── init.sql                   -> Auto-imported database schema for Docker
├── config/
│   ├── database.php               -> DB credentials (not committed to Git)
│   ├── database.example.php       -> Template for database.php
│   ├── ai.php                     -> Gemini API key (not committed to Git)
│   └── ai.example.php             -> Template for ai.php
├── controllers/                   -> AuthController, AssetController, TransferController,
│                                     MemberController, UserController, DashboardController
├── models/                        -> Company, User, Asset, TransferLog
├── views/                         -> auth/, assets/, transfers/, members/, layouts/,
│                                     dashboard.php, change_password.php
├── public/                        -> index.php (entry point), uploads/, css/, js/
├── core/                          -> Router, Controller, Model, Database
├── helpers/                       -> SessionHelper, AuthHelper, SecurityHelper, MailHelper
├── libraries/PHPMailer/           -> PHPMailer files (not committed to Git)
├── sql/                           -> database.sql (database creation script)
├── docs/uml/                      -> UML diagrams
└── .htaccess                      -> Routes all requests to public/index.php
```

---

## Troubleshooting

**Docker: "dependency failed to start: db is unhealthy"**
Increase the health check timeout in `docker-compose.yml` (`start_period: 30s`, `retries: 20`) and run `docker-compose down --volumes` then `docker-compose up --build`.

**Docker: "Class not found" error**
Make sure the controller filename casing matches exactly (e.g. `DashboardController.php` not `DashBoardController.php`) — Linux/Docker is case-sensitive.

**XAMPP: Blank page / "Class not found"**
Make sure Apache's `mod_rewrite` is enabled and `AllowOverride All` is set in `httpd.conf`, then restart Apache.

**XAMPP: 404 Not Found on every page**
Check that `BASE_URL` in `public/index.php` exactly matches your project folder name (case-sensitive).

**Images not showing**
Confirm `public/uploads/assets/` exists. Image `<img>` tags use `BASE_URL . '/public/uploads/...'` — check this path in your views.

**Emails not sending / SSL certificate error**
On local XAMPP, Gmail SMTP may fail with SSL verification. Ensure `helpers/MailHelper.php` includes:
```php
$mail->SMTPOptions = [
    'ssl' => [
        'verify_peer'       => false,
        'verify_peer_name'  => false,
        'allow_self_signed' => true
    ]
];
```

**"SecurityHelper" / "MailHelper" not found**
Make sure `public/index.php` includes all required files:
```php
require __DIR__ . '/../helpers/SessionHelper.php';
require __DIR__ . '/../helpers/AuthHelper.php';
require __DIR__ . '/../helpers/SecurityHelper.php';
require __DIR__ . '/../helpers/MailHelper.php';
require __DIR__ . '/../core/Database.php';
require __DIR__ . '/../core/Router.php';
require __DIR__ . '/../core/Controller.php';
require __DIR__ . '/../core/Model.php';
```

**"Invalid parameter number" SQL error**
Check that the number of `?` placeholders in a prepared statement matches the values passed to `execute([...])`.

**Asset deletion blocked by foreign key error**
This happens when an asset has transfer_log records referencing it. The `Asset::delete()` method should delete related transfer_log entries first before deleting the asset.

---

## License

All Rights Reserved. This project is proprietary and was developed for personal/educational purposes. No part of this code may be copied, distributed, modified, or used without explicit permission from the author.