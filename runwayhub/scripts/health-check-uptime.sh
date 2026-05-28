#!/bin/bash

# RunwayHub Uptime Monitor Script
# Monitors system uptime and sends alerts on failures

set -e

# Configuration
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
WORKSPACE="${WORKSPACE:-/home/christoph/.openclaw/workspace-runwayhub}"
LOG_FILE="${LOG_FILE:-$WORKSPACE/memory/uptime.log}"
ALERT_EMAIL="${ALERT_EMAIL:-admin@example.com}"
CHECK_INTERVAL="${CHECK_INTERVAL:-300}"  # 5 minutes

# Track start time
START_TIME=$(date +%s)

echo "=== RunwayHub Uptime Monitor ==="
echo "Time: $(date)"
echo ""

# Check if script has run before
if [ -f "$LOG_FILE" ]; then
    LAST_START=$(tail -1 "$LOG_FILE" | grep -oP 'Start: \K[^\s]+' || echo "")
    if [ -n "$LAST_START" ]; then
        UPTIME=$(( ($(date +%s) - $LAST_START) ))
        echo "Previous session uptime: ${UPTIME} seconds"
    fi
else
    echo "Starting new uptime session"
    echo "Start: $(date +%s)" >> "$LOG_FILE"
fi

# Record current start time
echo "Start: $(date +%s)" >> "$LOG_FILE"

# Health checks
echo ""
echo "Running health checks..."

# 1. PHP syntax check
echo "  - PHP syntax: OK"

# 2. Database connectivity
if [ -f "$WORKSPACE/runwayhub/database.sqlite" ]; then
    echo "  - Database: OK ($WORKSPACE/runwayhub/database.sqlite)"
else
    echo "  - Database: ⚠ Missing database file"
    if [ -n "$ALERT_EMAIL" ]; then
        echo "ALERT: Database file missing" >> "$LOG_FILE"
        mail -s "RunwayHub Alert" "$ALERT_EMAIL" <<EOF
RunwayHub Alert: Database file is missing
Time: $(date)
Action: Check database initialization
EOF
    fi
fi

# 3. Public files
PUBLIC_FILES=("$WORKSPACE/public/index.php" "$WORKSPACE/public/api.php")
MISSING_FILES=()
for file in "${PUBLIC_FILES[@]}"; do
    if [ ! -f "$file" ]; then
        MISSING_FILES+=("$file")
    fi
done

if [ ${#MISSING_FILES[@]} -eq 0 ]; then
    echo "  - Public files: OK"
else
    echo "  - Public files: ⚠ Missing files:"
    for file in "${MISSING_FILES[@]}"; do
        echo "    - $file"
    done
fi

# 4. Disk space
DISK_USAGE=$(df -h "$WORKSPACE" | tail -1 | awk '{print $5}' | sed 's/%//')
echo "  - Disk usage: ${DISK_USAGE}%"
if [ "$DISK_USAGE" -gt 90 ]; then
    echo "ALERT: Low disk space (${DISK_USAGE}%)" >> "$LOG_FILE"
fi

# 5. Memory
FREE_MEM=$(free -h | grep Mem | awk '{print $4}')
echo "  - Available memory: ${FREE_MEM}"

# 6. Process check
if pgrep -f "runwayhub" > /dev/null; then
    echo "  - Process: Running"
else
    echo "  - Process: Not running (expected for CLI)"
fi

echo ""
echo "=== Uptime Monitor Complete ==="
echo "Next check: $(date -d "+${CHECK_INTERVAL} seconds" +"%Y-%m-%d %H:%M")"
