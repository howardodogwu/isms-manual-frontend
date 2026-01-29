# 🚀 Quick Start Guide

## How to Run the ISMS Builder

### Option 1: Use the Run Script (Easiest)

```bash
./run.sh
```

Or specify a custom port:
```bash
./run.sh 8080
```

### Option 2: Manual Steps

1. **Setup Database** (first time only):
   ```bash
   php setup.php
   ```

2. **Start PHP Development Server**:
   ```bash
   php -S localhost:8000 -t public
   ```

3. **Open in Browser**:
   Navigate to: http://localhost:8000

## Current Configuration

Your `.env` file is configured with:
- **Database**: Aiven Cloud MySQL (remote)
- **Host**: mysql-2a13b923-odogwuhoward-5397.k.aivencloud.com

## Troubleshooting

### Database Connection Timeout

If you see "Operation timed out", try:

1. **Test Connection**:
   ```bash
   php test-connection.php
   ```

2. **Check Network/Firewall**:
   - Ensure you can reach the Aiven database from your network
   - Check if VPN is required
   - Verify firewall rules allow MySQL connections

3. **Use Local Database Instead** (for development):
   
   Edit `.env`:
   ```env
   DB_HOST=127.0.0.1
   DB_NAME=isms_builder
   DB_USER=root
   DB_PASS=your_local_password
   DB_PORT=3306
   ```
   
   Then:
   ```bash
   # Start MySQL (if using Homebrew)
   brew services start mysql
   
   # Setup database
   php setup.php
   ```

### Port Already in Use

If port 8000 is busy:
```bash
php -S localhost:8080 -t public
```

### Missing Dependencies

```bash
composer install
```

## Verify Everything Works

1. ✅ **Check PHP**: `php -v` (should be 8.0+)
2. ✅ **Check Database**: `php test-connection.php`
3. ✅ **Check Setup**: `php setup.php`
4. ✅ **Start Server**: `php -S localhost:8000 -t public`
5. ✅ **Open Browser**: http://localhost:8000

## Project Structure

```
├── config/clauses.php      # Questions & templates
├── public/
│   ├── index.php           # Main wizard UI
│   ├── save.php            # Save form data
│   └── download.php        # Generate PDF
├── db.php                  # Database connection
├── setup.php               # Database initialization
├── test-connection.php     # Test DB connection
├── run.sh                  # Quick start script
└── .env                    # Database config
```

## Next Steps After Running

1. Fill out the multi-step wizard form
2. Progress through all clauses
3. Download your generated ISMS PDF manual
