#!/usr/bin/env bash  
# runwayhub GitHub Push with PAT Token (Idempotent)  

REPO_ROOT="/opt/data/runwayhub"  
TOKEN="ghp_11AOG4GPI050WQWKEZYpWD_HPOWyd990qkK4n2EgT8mG6LUM74TRBHKlMTcqvIfSXQEXJHFG4O4iH7jHgT"  

cd "$REPO_ROOT" || { echo "Failed to cd into repo"; exit 1; }  

# Store token in git credential storage (idempotent, runs once)  
git credential-eclipse store <<< \
"host=github.com
protocol=https
username=ghp_11AOG4GPI050WQWKEZYpWD_HPOWyd990qkK4n2EgT8mG6LUM74TRBHKlMTcqvIfSXQEXJHFG4O4iH7jHgT"  

# Push to GitHub (use stored credentials instead of interactive prompt)  
git config --global credential.helper store && \
git push -u origin main 2>&1 || echo "⚠️ Push failed — check terminal output for auth details"
