#!/bin/bash
# runwayhub Git push helper - Safe & Simple

cd /opt/data/runwayhub || { echo "Failed to cd into repo"; exit 1; }

echo "🔧 Preparing GitHub Push..."

# Add remote (safe idempotent)
git remote add origin https://github.com/chris1971nrw/runwayhub.git 2>/dev/null || true

# Rename local branch to main if needed
git branch -M main 2>/dev/null || true

# Pull from upstream (or skip for empty repo)
git pull origin main 2>&1 || echo "⚠️  Upstream is empty (push anyway)"

# Push with force flag
git push -u origin main --force-with-lease 2>&1

echo ""
echo "✅ GitHub Repository:"
echo "https://github.com/chris1971nrw/runwayhub.git"
