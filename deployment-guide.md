# Deployment Guide for SPK Sekolah

Since this is a PHP application with MySQL database, here are the recommended deployment options:

## Option 1: Traditional PHP Hosting

Most suitable for your application as it supports PHP and MySQL out of the box.

### Recommended Providers:

- Hostinger
- InMotion Hosting
- Bluehost
- cPanel hosting providers

### Deployment Steps:

1. Export your MySQL database:

```sql
mysqldump -u root -p spk_jurusan > spk_jurusan.sql
```

2. Zip your project files:

```bash
zip -r spk_sekolah.zip . -x "*.git*"
```

3. Upload to hosting:

- Login to your hosting control panel
- Create a MySQL database and import spk_jurusan.sql
- Upload the zip file to public_html
- Extract the files
- Update config/database.php with new database credentials

## Option 2: Heroku Deployment

Heroku supports PHP applications natively.

1. Install Heroku CLI and Git

2. Create Procfile in your project root:

```
web: vendor/bin/heroku-php-apache2
```

3. Create composer.json if not exists:

```json
{
  "require": {
    "php": "^7.4.0",
    "ext-mysqli": "*"
  }
}
```

4. Initialize Git and deploy:

```bash
git init
git add .
git commit -m "Initial commit"
heroku create spk-sekolah
git push heroku main
```

5. Set up database:

```bash
heroku addons:create cleardb:ignite
```

6. Update database configuration:

```php
// config/database.php
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$host = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$database = substr($url["path"], 1);
```

## Option 3: DigitalOcean App Platform

1. Create account on DigitalOcean
2. Create new App
3. Connect your GitHub repository
4. Add MySQL database from Resources tab
5. Configure environment variables for database connection
6. Deploy

## Important Deployment Checklist:

1. Database Configuration:

- Update database credentials in config/database.php
- Ensure database connection is secure
- Back up your database regularly

2. Security Measures:

- Remove/protect config files
- Set proper file permissions
- Enable HTTPS
- Update all dependencies

3. Performance:

- Enable PHP opcache
- Configure proper PHP memory limits
- Set up caching if needed

4. Monitoring:

- Set up error logging
- Monitor database performance
- Set up backup systems

## Local Development vs Production

Create separate configuration files for development and production:

```php
// config/database.php
if ($_SERVER['SERVER_NAME'] === 'localhost') {
    // Local database settings
    $host = 'localhost';
    $username = 'root';
    $password = 'root';
    $database = 'spk_jurusan';
} else {
    // Production database settings
    $host = getenv('DB_HOST');
    $username = getenv('DB_USER');
    $password = getenv('DB_PASS');
    $database = getenv('DB_NAME');
}
```

## Environment Variables

Create a .env file for local development (don't commit to git):

```
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=spk_jurusan
```

Add .env to .gitignore:

```
echo ".env" >> .gitignore
```

## SSL/HTTPS Setup

Most hosting providers offer free SSL certificates through Let's Encrypt. Make sure to:

1. Enable HTTPS
2. Redirect HTTP to HTTPS
3. Update all asset URLs to use HTTPS

## Post-Deployment Checks:

1. Test all functionality
2. Verify database connections
3. Check error logs
4. Monitor performance
5. Test on multiple browsers
6. Verify all forms work
7. Check file permissions
