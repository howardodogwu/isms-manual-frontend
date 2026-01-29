# PurpleWasp ISMS Builder

An Information Security Management System (ISMS) builder that generates ISO 27001 compliant documentation through an interactive wizard.

## Prerequisites

- PHP 8.0 or higher
- MySQL/MariaDB
- Composer

## Quick Start

### 1. Install Dependencies

```bash
composer install
```

### 2. Configure Database

Edit `.env` file with your database credentials:

```env
DB_HOST=127.0.0.1
DB_NAME=isms_builder
DB_USER=root
DB_PASS=your_password
DB_PORT=3306
```

### 3. Setup Database

Run the setup script to create the database and tables:

```bash
php setup.php
```

### 4. Start Development Server

```bash
php -S localhost:8000 -t public
```

### 5. Open in Browser

Navigate to: http://localhost:8000

## Project Structure

```
/purplewasp-isms
├── /config
│   └── clauses.php       # Questions & Templates (The "Brain")
├── /public
│   ├── index.php         # Wizard UI (Multi-step form)
│   ├── save.php          # Saves form data
│   └── download.php      # Generates PDF
├── db.php                # Database Connection
├── .env                  # Database Credentials
├── setup.php             # Database initialization script
├── Dockerfile            # For Render Deployment
└── composer.json
```

## Features

- ✅ Multi-step wizard interface
- ✅ Progress tracking
- ✅ Session-based user tracking
- ✅ PDF generation using dompdf
- ✅ ISO 27001 compliant structure
- ✅ Responsive design with Tailwind CSS

## Troubleshooting

### Database Connection Issues

- Ensure MySQL is running: `brew services start mysql` (macOS)
- Check `.env` credentials match your database
- Verify database exists: `mysql -u root -p -e "SHOW DATABASES;"`

### Port Already in Use

If port 8000 is busy, use a different port:
```bash
php -S localhost:8080 -t public
```

### Missing Dependencies

If you see "Class not found" errors:
```bash
composer install
```

## Production Deployment

See `Dockerfile` for Render.com deployment configuration.
