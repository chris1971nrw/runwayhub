#!/bin/bash

# RunwayHub Database Backup Script
# Automates SQLite database backups with timestamp

set -e

# Configuration
BACKUP_DIR="${BACKUP_DIR:-/home/christoph/.openclaw/workspace-runwayhub/backups}"
DB_FILE="${DB_FILE:-/home/christoph/.openclaw/workspace-runwayhub/runwayhub/database.sqlite}"
RETENTION_DAYS="${RETENTION_DAYS:-30}"
COMPRESSION="${COMPRESSION:-gzip}"

# Create backup directory if it doesn't exist
mkdir -p "$BACKUP_DIR"

# Generate timestamp for backup filename
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_FILE="${BACKUP_DIR}/runwayhub-backup-${TIMESTAMP}.db"

# Perform backup
if [ -f "$DB_FILE" ]; then
    cp "$DB_FILE" "$BACKUP_FILE"
    
    # Compress if requested
    if [ "$COMPRESSION" = "gzip" ]; then
        gzip "$BACKUP_FILE"
        echo "Database backed up: ${BACKUP_FILE}.gz"
    else
        echo "Database backed up: $BACKUP_FILE"
    fi
    
    # Clean old backups
    find "$BACKUP_DIR" -name "*.db*" -mtime +$RETENTION_DAYS -delete 2>/dev/null || true
    
    echo "Backup complete! Retained backups from last $RETENTION_DAYS days."
else
    echo "Error: Database file not found at $DB_FILE"
    exit 1
fi
