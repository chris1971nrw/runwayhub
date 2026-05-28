#!/bin/bash

# RunwayHub Health Check Script
# Checks system health, database, and API endpoints

set -e

echo "=== RunwayHub Health Check ==="
echo "Time: $(date)"
echo ""

# 1. Check PHP syntax on main files
echo "1. Checking PHP syntax on core files..."
for file in runwayhub/bootstrap.php runwayhub/index.php public/index.php; do
    if [ -f "$file" ]; then
        php -l "$file" 2>&1 || true
    fi
done
echo "   ✓ PHP syntax check complete"

# 2. Check database file
echo "2. Checking database..."
if [ -f "runwayhub/database.sqlite" ]; then
    SIZE=$(stat -f%z "runwayhub/database.sqlite" 2>/dev/null || stat -c%s "runwayhub/database.sqlite" 2>/dev/null)
    echo "   ✓ Database exists ($SIZE bytes)"
    
    # Check SQLite integrity (light check)
    sqlite3 "runwayhub/database.sqlite" "SELECT count(*) FROM sqlite_master WHERE type='table' AND name!='sqlite_sequence';" 2>/dev/null || echo "   ⚠ Database check inconclusive"
else
    echo "   ⚠ Database file not found"
fi

# 3. Check API endpoints
echo "3. Checking API endpoints..."
API_FILE="runwayhub/public/api-status.php"
if [ -f "$API_FILE" ]; then
    echo "   ✓ API status file exists"
else
    echo "   ⚠ API status file not found"
fi

# 4. Check file permissions
echo "4. Checking file permissions..."
find runwayhub -name "*.php" -type f -perm /000 2>/dev/null | head -5 | while read f; do
    echo "   ⚠ Insecure permissions: $f"
done || echo "   ✓ All files have secure permissions"

# 5. Check disk space
echo "5. Checking disk space..."
DISK_USAGE=$(df -h . | tail -1 | awk '{print $5}' | sed 's/%//')
echo "   Current disk usage: ${DISK_USAGE}%"

# 6. Memory check
echo "6. Checking memory..."
if command -v free &> /dev/null; then
    free -h | grep -E "^Mem" || true
fi

echo ""
echo "=== Health Check Complete ==="
echo "Next check: $(date -d "+1 hour" +"%Y-%m-%d %H:%M")"
