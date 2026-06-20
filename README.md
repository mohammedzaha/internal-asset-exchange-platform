# Internal Asset Exchange Platform

A multi-tenant internal web platform that lets companies manage and transfer unused equipment between departments — reducing waste and avoiding duplicate purchases.

Built with **PHP (custom MVC)**, **MySQL**, **Bootstrap 5**, and **PHPMailer** for email notifications.

---

## Documentation
- UML diagrams: `docs/uml/`

---

## Features

- Multi-company workspaces with unique company codes (full data isolation)
- Secure authentication (email + password + company code), password hashing
- Role-based access: **Team Leader** and **Team Member**
- Asset management: add (with image upload), view, search/filter, delete (same-department guard)
- Transfer workflow: request → approve/reject → history + savings tracking (Leader-only access)
- Member management (Leader adds members, auto-generated temporary password)
- Self-service password change
- Dashboard with statistics (available/transferred assets, total savings, top valued assets, pending approvals)
- Real email notifications (via Gmail SMTP + PHPMailer) for: company creation, new member onboarding, transfer requests, approvals/rejections, password changes
- Popup (JS alert) feedback for every user action

---

## Requirements

- **XAMPP** (or any Apache + PHP 8+ + MySQL stack) — [download here](https://www.apachefriends.org/)
- A Gmail account (for sending email notifications) with an **App Password**
- A web browser

No Composer needed — PHPMailer is included as plain files.

---

## Setup Instructions

### 1. Get the project files

Download/extract the project folder. Place it inside your XAMPP `htdocs` directory, for example:

```
C:\xampp\htdocs\internal-asset-exchange-platform\
```

### 2. Start Apache and MySQL

Open the **XAMPP Control Panel** and click **Start** next to both **Apache** and **MySQL**.

### 3. Create the database

1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click the **Import** tab.
3. Click **Choose File** and select `sql/database.sql` from the project folder.
4. Click **Go**.

This automatically creates the `asset_exchange` database with all 4 tables (`companies`, `users`, `assets`, `transfer_log`) and their relationships.

> The SQL file includes `CREATE DATABASE IF NOT EXISTS asset_exchange;`, so no manual database creation is needed first.

### 4. Configure the database connection

Copy `config/database.example.php` to `config/database.php` and adjust if needed (XAMPP defaults shown below usually work as-is):

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
define('BASE_URL', '/internal-asset-exchange-platform');
```

Make sure the value matches the **name of your project folder** inside `htdocs`. For example, if your folder is `C:\xampp\htdocs\my-project`, set:

```php
define('BASE_URL', '/my-project');
```

### 6. Set up email notifications (PHPMailer)

1. Download [PHPMailer](https://github.com/PHPMailer/PHPMailer) (Code → Download ZIP).
2. From the extracted `src/` folder, copy `PHPMailer.php`, `SMTP.php`, and `Exception.php` into `libraries/PHPMailer/` in your project.
3. Get a Gmail App Password:
   - Go to `https://myaccount.google.com` → **Security**
   - Enable **2-Step Verification**
   - Go to `https://myaccount.google.com/apppasswords` → generate a new App Password
4. Open `helpers/MailHelper.php` and set your credentials:
```php
$mail->Username = 'your-gmail@gmail.com';
$mail->Password = 'your-16-char-app-password';
$mail->setFrom('your-gmail@gmail.com', 'Asset Exchange Platform');
```

### 7. Make the uploads folder writable

Confirm the folder `public/uploads/assets/` exists. On Windows/XAMPP this works out of the box. On Linux/Mac, run:

```bash
chmod -R 775 public/uploads/assets/
```

---

## Running the Project

Open your browser and go to:

```
http://localhost/internal-asset-exchange-platform/
```

(replace `internal-asset-exchange-platform` with your actual folder name, matching `BASE_URL`)

You should land on the **Login page**.

---

## First Use — Quick Walkthrough

1. **Create a company**
   - Click **"Create a company workspace"** on the login page.
   - Fill in: company name, your name, email, password, department.
   - Submit → you'll be redirected to the dashboard as **Team Leader**, a popup shows your unique **Company Code**, and a welcome email is sent.

2. **Log in**
   - Use your email, password, and the company code shown earlier.

3. **Add an asset** (Leader or Member)
   - Click **"Add Asset"** in the navbar.
   - Fill in name, category, value, condition, description, and optionally upload an image.
   - Submit → the asset appears under **"View Assets"**.

4. **Add a team member** (Leader only)
   - Click **"Add Member"** → fill in name, email, department, role.
   - A temporary password is generated, shown in a popup, and emailed to the new member.

5. **Request a transfer** (any user, on an asset from a different department)
   - Open an asset's details page and click **"Request Transfer"**.
   - The company Leader receives an email notification.

6. **Approve/Reject** (Team Leader only)
   - Go to **"Pending Approvals"** to review and approve or reject the request.
   - The requester receives an email with the decision.

7. **Check history & savings** (Team Leader only)
   - Go to **"All Transfers"** to see completed transfers and total savings.

8. **Change password** (any user)
   - Click **"Change Password"** in the navbar — requires current password + new password (min 6 characters).

9. **Dashboard statistics**
   - Available and transferred asset counts, top 3 valued assets are visible to all users.
   - Total savings and pending approvals count are visible to **Team Leaders only**.

---

## Testing Multi-Tenant Isolation

Create a **second company** (different email, new "Create Company" form) and confirm:
- It gets its own unique company code
- Assets/users/transfers from Company A are **not visible** to Company B

---

## Project Structure

```
internal-asset-exchange-platform/
├── cahier_des_charges.pdf        -> Full project specification
├── config/
│   ├── database.php              -> DB connection settings (not committed to Git)
│   └── database.example.php      -> Template for database.php
├── controllers/                  -> AuthController, AssetController, TransferController,
│                                     MemberController, UserController, DashboardController
├── models/                       -> Company, User, Asset, TransferLog
├── views/                        -> auth/, assets/, transfers/, members/, layouts/,
│                                     dashboard.php, change_password.php
├── public/                       -> index.php (entry point), uploads/, css/, js/
├── core/                         -> Router, Controller, Model, Database
├── helpers/                      -> SessionHelper, AuthHelper, SecurityHelper, MailHelper
├── libraries/PHPMailer/          -> PHPMailer files (not committed to Git)
├── sql/                          -> database.sql (database creation script)
├── docs/uml/                     -> UML diagrams
└── .htaccess                     -> routes all requests to public/index.php
```

---

## Troubleshooting

**Blank page / "Class not found" error**
Make sure Apache's `mod_rewrite` is enabled and `AllowOverride All` is set for `htdocs` in `httpd.conf`, then restart Apache.

**404 Not Found on every page**
Check that `BASE_URL` in `public/index.php` exactly matches your project folder name (case-sensitive).

**Images not showing**
Confirm `public/uploads/assets/` exists and contains the uploaded image files, and that image `<img>` tags use `BASE_URL . '/public/...'`.

**"SecurityHelper" / "MailHelper" not found or similar class errors**
Make sure `public/index.php` includes all required files at the top:
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

**Emails not sending / SSL certificate error**
On local XAMPP environments, Gmail SMTP may fail with an SSL verification error. Ensure `helpers/MailHelper.php` includes:
```php
$mail->SMTPOptions = [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    ]
];
```

**"Invalid parameter number" SQL error**
Check that the number of `?` placeholders in a prepared SQL statement matches the number of values passed to `execute([...])`.

---

## License

All Rights Reserved. This project is proprietary and was developed for personal/educational purposes. No part of this code may be copied, distributed, modified, or used without explicit permission from the author.