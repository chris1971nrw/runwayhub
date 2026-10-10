#!/usr/bin/env bash  
# runwayhub MVP Setup Script autonomus subagent orchestration

set -e  

echo "🚀 Subagent Tasks Start..."  

mkdir -p /opt/data/runwayhub/{views,app,storage/logs}  
chmod +x /opt/data/runwayhub/setup.sh 2>/dev/null || true  
ls -la /opt/data/runwayhub/*.sql *.sh *.md | grep -v context || echo "Setup-Files bereits vorhanden"  
