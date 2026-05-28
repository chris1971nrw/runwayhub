#!/bin/bash

# RunwayHub v1.0.0 - GitHub Release Creator
# This script creates a release with assets

set -e

REPO="chris1971nrw/runwayhub"
VERSION="v1.0.0"
RELEASE_FILE="runwayhub/releases/v1.0.0.tar.gz"
RELEASE_NOTES="GITHUB_RELEASE.md"

echo "============================"
echo "RunwayHub v1.0.0 - GitHub Release Creator"
echo "============================"
echo ""

# Check if release file exists
if [ ! -f "runwayhub/releases/v1.0.0.tar.gz" ]; then
    echo "❌ Release file not found: runwayhub/releases/v1.0.0.tar.gz"
    echo ""
    echo "Creating release package from runwayhub/releases/v1.0.0/..."
    
    # Create tarball from release directory
    mkdir -p /tmp/runwayhub-v1.0.0
    cp -r runwayhub/releases/v1.0.0/* /tmp/runwayhub-v1.0.0/
    cd /tmp/runwayhub-v1.0.0
    tar -czf ~/runwayhub-v1.0.0.tar.gz .
    mv ~/runwayhub-v1.0.0.tar.gz /home/christoph/.openclaw/workspace-runwayhub/runwayhub/releases/v1.0.0.tar.gz
    cd /home/christoph/.openclaw/workspace-runwayhub
    echo "✅ Release package created"
    ls -lh runwayhub/releases/v1.0.0.tar.gz
    echo ""
fi

# Check if GitHub CLI is authenticated
if ! gh auth status >/dev/null 2>&1; then
    echo "⚠️  GitHub CLI not authenticated"
    echo ""
    echo "To create release, you need to:"
    echo "1. Run: gh auth login"
    echo "2. Or set GH_TOKEN environment variable"
    echo ""
    echo "Or manually create release at:"
    echo "https://github.com/chris1971nrw/runwayhub/releases/new"
    echo ""
    echo "Click 'Draft a release'"
    echo "Tag: v1.0.0"
    echo "Title: v1.0.0"
    echo "Description: Contents of GITHUB_RELEASE.md"
    echo ""
    echo "After creation, upload these files:"
    ls -lh runwayhub/releases/v1.0.0/*.md 2>/dev/null | awk '{print "- " $NF}'
    echo ""
    echo "Or upload the tarball:"
    ls -lh runwayhub/releases/v1.0.0.tar.gz 2>/dev/null | awk '{print "- " $NF}'
    echo ""
    exit 1
fi

# Check if release already exists
RELEASES=$(gh release list --repo "$REPO" --limit 10)
if echo "$RELEASES" | grep -q "$VERSION"; then
    echo "⚠️  Release $VERSION already exists"
    echo ""
    echo "Checking existing release..."
    gh release view "$VERSION" --repo "$REPO" --json tagName --json name
    exit 0
fi

# Create release
echo "Creating release for $REPO..."
echo "Tag: $VERSION"
echo ""

# Read release notes
if [ -f "$RELEASE_NOTES" ]; then
    RELEASE_NOTES_CONTENT=$(cat "$RELEASE_NOTES")
else
    echo "⚠️  Release notes file not found: $RELEASE_NOTES"
    RELEASE_NOTES_CONTENT=""
fi

# Create release
RELEASE=$(gh release create "$VERSION" --repo "$REPO" --notes "$RELEASE_NOTES_CONTENT" 2>&1)

if [ $? -eq 0 ]; then
    echo "✅ Release created successfully!"
    echo ""
    echo "Release URL:"
    echo "$RELEASE" | grep -o 'https://[a-zA-Z0-9./_-]*'
    echo ""
    echo "Next step: Upload release assets"
    echo ""
    echo "Available files to upload:"
    ls -lh runwayhub/releases/v1.0.0/*.md 2>/dev/null | awk '{print "- " $NF}'
    echo ""
else
    echo "❌ Release creation failed"
    echo "$RELEASE"
    exit 1
fi
