# AAUJMR Installation Guide

## 1. Local Windows/XAMPP

### Requirements

- Windows 10/11
- XAMPP with Apache, PHP and MySQL/MariaDB
- PHP 8.1+ recommended
- MySQL/MariaDB with PDO MySQL enabled

### Steps

1. Install XAMPP, normally into `C:\xampp`.
2. Open XAMPP Control Panel.
3. Start **Apache** and **MySQL**.
4. Extract this project to:

`C:\xampp\htdocs\aaujmr\`

5. Confirm the file exists at:

`C:\xampp\htdocs\aaujmr\install.php`

6. Open:

`http://localhost/aaujmr/install.php`

7. For a standard XAMPP installation enter:

| Setting | Value |
|---|---|
| MySQL Host | `localhost` |
| Database Name | `aaujmr` |
| Database User | `root` |
| Database Password | blank |
| Site URL Path | `/aaujmr` |

8. Set your own admin username and password.
9. Click **Install AAUJMR**.
10. Open `http://localhost/aaujmr/`.

### What the installer does

- Creates the `aaujmr` database when the MySQL account has permission to create it.
- Creates all journal tables.
- Imports the existing journal/article seed data.
- Loads AAUJMR site settings.
- Loads the editorial board.
- Creates/updates the admin account with the password you choose.
- Writes database credentials into `src/config/config.php`.
- Renames the installer after success.

### If MySQL root has a password

Enter that password on the installer form. Do not put it into the source code before installation.

## 2. cPanel

### Create database first

In cPanel:

1. Open **MySQL Databases**.
2. Create a database.
3. Create a database user.
4. Add the user to the database.
5. Grant **ALL PRIVILEGES**.

Your cPanel database name may look like:

`account_aaujmr`

and the user may look like:

`account_aaujmruser`

### Upload

Upload and extract the project into either:

- `public_html/aaujmr/` for `https://domain.com/aaujmr/`
- the document root of a subdomain/domain for `https://journal.domain.com/`

Then visit the installer.

For `https://domain.com/aaujmr/`, use:

`/aaujmr`

For a domain/subdomain whose document root is the application directory, use an empty Site URL Path.

Enter the cPanel database credentials and complete installation.

### After installation

Delete `install-completed.php` from the web directory. Confirm that the public site, article archive, editorial board, contact page and admin login all work.

## 3. Common problems

### Database connection failed

Check:

- MySQL is running.
- Database name is correct.
- Database username/password are correct.
- cPanel database user has ALL PRIVILEGES.
- PHP has PDO MySQL enabled.

### 404 on `/archive` or `/article`

Make sure Apache `mod_rewrite` is enabled and `.htaccess` is present. On XAMPP, Apache must allow overrides for the `htdocs` directory.

### CSS/images do not load

Check the Site URL Path. Use `/aaujmr` when the application is in `htdocs/aaujmr`, or an empty value when it is directly at the domain/subdomain document root.

### Blank/500 page

Temporarily enable PHP error logging in the server configuration and inspect the PHP/Apache error log. Do not enable detailed errors on a production site.

## 4. Security checklist before production

- Change/choose a strong admin password.
- Delete the installer after installation.
- Enable HTTPS.
- Keep PHP and MariaDB/MySQL supported and updated.
- Restrict access to administrative accounts.
- Back up the database and uploaded PDFs regularly.
