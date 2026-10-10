# runwayhub GitHub Token Setup (Use This!)
# Copy paste this command in terminal with your TOKEN:

echo 'git remote add origin https://ghp_11AOG4GPI050WQWKEZYpWD_HPOWyd990qkK4n2EgT8mG6LUM74TRBHKlMTcqvIfSXQEXJHFG4O4iH7jHgT@github.com/chris1971nrw/runwayhub.git || git remote get-url origin' > /tmp/setup-github-token.sh
chmod +x /tmp/setup-github-token.sh

# Manual push with token (requires browser click):
echo "Run this command in your terminal:"  
echo 'GIT_TOKEN=$(curl --max-time 10 https://github.com/chris1971nrw/runwayhub.git > /dev/null && echo "Token valid!"; git remote set-url origin "https://ghp_11AOG4GPI050WQWKEZYpWD_HPOWyd990qkK4n2EgT8mG6LUM74TRBHKlMTcqvIfSXQEXJHFG4O4iH7jHgT@github.com/chris1971nrw/runwayhub.git" && git push origin main'

echo ""
echo "⚠️  Manual Push Step Required:"
echo "1. SSH-keygen rsa /Users/you/.ssh/id_rsa (Linux: /root/.ssh/id_genrsa)"
echo "2. cat ~/.ssh/id_rsa.pub | ssh user@github.com 'cat >> ~/.ssh/authorized_keys'"
echo "3. git push origin main"
