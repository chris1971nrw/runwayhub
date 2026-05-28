#!/bin/bash

# RunwayHub Automated Backup Script
# Runs scheduled backups with rotation

set -e

# Configuration
BACKUP_DIR="${BACKUP_DIR:-/home/christoph/.openclaw/workspace-runwayhub/backups}"
DB_FILE="${DB_FILE:-/home/christoph/.openclaw/workspace-runwayhub/runwayhub/database.sqlite}"
RETENTION_DAYS="${RETENTION_DAYS:-30}"

# Create directories
mkdir -p "$BACKUP_DIR"

# Generate timestamp
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_FILE="${BACKUP_DIR}/runwayhub-backup-${TIMESTAMP}.db.gz"

echo "=== RunwayHub Backup ==="
echo "Time: $(date)"
echo "Source: $DB_FILE"
echo "Backup: $BACKUP_FILE"

# Create backup
if [ -f "$DB_FILE" ]; then
    cp "$DB_FILE" "$BACKUP_FILE"
    gzip "$BACKUP_FILE"
    echo "✓ Backup created: ${BACKUP_FILE}.gz"
    echo "  Size: $(ls -lh "$BACKUP_FILE" | awk '{print $5}')"
else
    echo "✗ Database file not found"
    exit 1
fi

# Rotate old backups
echo ""
echo "=== Rotating Old Backups ==="
find "$BACKUP_DIR" -name "*.db.gz" -type f -mtime +${RETENTION_DAYS} -delete 2>/dev/null || true
echo "  Retention: $RETENTION_DAYS days"

# Count backups
BACKUP_COUNT=$(find "$BACKUP_DIR" -name "*.db.gz" -type f | wc -l)
echo ""
echo "=== Summary ==="
echo "Active backups: $BACKUP_COUNT"
echo "Latest backup: $(ls -t "$BACKUP_DIR"/*.db.gz 2>/dev/null | head -1 || echo 'none')"

# Cleanup
rm -f "$BACKUP_FILE" 2>/dev/null || true

echo ""
echo "=== Backup Complete ==="
