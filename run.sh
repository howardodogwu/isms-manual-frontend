#!/bin/bash

# PurpleWasp ISMS Builder - Quick Start Script

echo "🔒 PurpleWasp ISMS Builder"
echo "=========================="
echo ""

# Check if PHP is available
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed or not in PATH"
    exit 1
fi

echo "✅ PHP found: $(php -v | head -n 1)"
echo ""

# Check if vendor directory exists
if [ ! -d "vendor" ]; then
    echo "📦 Installing Composer dependencies..."
    composer install
    echo ""
fi

# Check if .env exists
if [ ! -f ".env" ]; then
    echo "⚠️  Warning: .env file not found. Using defaults."
    echo ""
fi

# Test database connection
echo "🔍 Testing database connection..."
php test-connection.php
echo ""

if [ $? -ne 0 ]; then
    echo "⚠️  Database connection failed. Please fix your .env file and try again."
    echo "   Or run: php setup.php"
    echo ""
    read -p "Continue anyway? (y/n) " -n 1 -r
    echo ""
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

# Start PHP development server
PORT=${1:-8000}
echo "🚀 Starting development server on http://localhost:$PORT"
echo "   Press Ctrl+C to stop"
echo ""

php -S localhost:$PORT -t public
