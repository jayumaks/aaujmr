# AAUJMR — Ambrose Alli University Journal of Medical Research

A PHP/MySQL journal website for the Faculty of Basic Medical Sciences, College of Medicine, Ambrose Alli University, Ekpoma, Nigeria.

## Fastest local XAMPP setup

1. Install XAMPP.
2. Start **Apache** and **MySQL**.
3. Extract this ZIP into `C:\xampp\htdocs\aaujmr\`.
4. Open `http://localhost/aaujmr/install.php`.
5. Leave the standard XAMPP database values as:
   - Host: `localhost`
   - User: `root`
   - Password: blank (unless you set a MySQL root password)
   - Database: `aaujmr`
6. Choose an admin username/password and click **Install AAUJMR**.
7. Open `http://localhost/aaujmr/`.

The installer creates the database, imports the complete schema and seed content, loads the editorial board, creates the admin account, and writes `src/config/config.php` for you.

## cPanel setup

1. Create a MySQL database and database user in cPanel and grant the user **ALL PRIVILEGES**.
2. Upload/extract the ZIP into the required document root, for example `public_html/aaujmr/`.
3. Open `https://your-domain.example/aaujmr/install.php`.
4. Enter the cPanel database host, database name, username and password. cPanel users normally cannot create databases from PHP, so create it in cPanel first.
5. For a domain/subdomain pointing directly to the application folder, leave **Site URL Path** empty. For a subfolder such as `/aaujmr`, enter `/aaujmr`.
6. Complete installation and remove `install-completed.php` after confirming the site works.

## Admin

Use the admin username/password created during installation. Never rely on a default production password.

## Important

The application is database-driven. Apache/PHP can display the shell of a PHP application without data, but the journal archive, articles, editorial board, settings and administration features require MySQL. This package includes the database installer so the database is configured before the frontend is used.

## AAUJMR information included

- Ambrose Alli University Journal of Medical Research (AAUJMR)
- Faculty of Basic Medical Sciences, College of Medicine
- Ambrose Alli University, Ekpoma, Nigeria
- International scope, peer-reviewed
- Publication: annually in June
- Submission emails: `aaujmr@aauekpoma.edu.ng` and `editoraaujmr@gmail.com`
- Vancouver referencing
- Author instructions supplied by the journal
