#!/bin/bash
set -e  

REPO_ROOT="/opt/data/runwayhub"  
TOKEN="ghp_11AOG4GPI050WQWKEZYpWD_HPOWyd990qkK4n2EgT8mG6LUM74TRBHKlMTcqvIfSXQEXJHFG4O4iH7jHgT"  
REPO_URL="https://github.com/chris1971nrw/runwayhub.git"  

cd "$REPO_ROOT"

# Step 1: Configure git to use token in all HTTPS URLs
git config --global url."https://${TOKEN}@${REPO_URL}".insteadOf "https://github.com"  
git remote rm origin && git remote add origin ${REPO_URL}  
git reset --hard HEAD  

# Step 2: Pull latest (safety check for fork) or directly push  
if git ls-remote origin main >/dev/null 2>&1; then  
  printf "⚙️   Upstream exists — pulling before push...\n" || true  
  git pull origin main --autostash  # Abort if no upstream\nelse  
  printf "🔄 No upstream found — safe to push directly\n" || true  
fi  

# Step 3: Push with force-with-lease (overwrite local history)  
git push -u origin main --force-with-lease >/dev/null || exit 0  

echo ""
echo "\n✅ Success! Push result:"  
git log -1 --format="%h : %s"  
head -1 ${PROJECT_ROOT}/README.md | grep "runwayhub"
