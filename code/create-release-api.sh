#!/bin/bash

# GitHub API Token
GITHUB_TOKEN="${GITHUB_TOKEN:-}"

if [ -z "$GITHUB_TOKEN" ]; then
    echo "❌ GITHUB_TOKEN ist erforderlich!"
    echo "Bitte setze GITHUB_TOKEN im .env oder als Environment-Variable."
    exit 1
fi

# Repository
REPOSITORY="chris1971nrw/runwayhub"

# Upload Asset
ASSET_URL="https://uploads.github.com/repos/${REPOSITORY}/releases/assets?name=runwayhub.tar.gz"

# Read Release Notes
NOTES=$(cat .github/RELEASE_NOTES.md)

# Create Release
RELEASE_RESPONSE=$(curl -s -X POST "https://api.github.com/repos/${REPOSITORY}/releases/tags/v1.0.0" \
  -H "Accept: application/vnd.github.v3+json" \
  -H "Authorization: Bearer ${GITHUB_TOKEN}" \
  -d "name=RunwayHub v1.0.0" \
  -d "body=${NOTES}" \
  -d "draft=false" \
  -d "prerelease=false")

# Check Response
STATUS=$(echo ${RELEASE_RESPONSE} | jq -r '.status')

if [ "${STATUS}" == "201" ]; then
    echo "✅ Release erstellt!"
    echo ${RELEASE_RESPONSE} | jq .
else
    echo "❌ Fehler beim Erstellen des Releases:"
    echo ${RELEASE_RESPONSE}
    exit 1
fi

# Upload Asset
UPLOAD_RESPONSE=$(curl -s -X POST ${ASSET_URL} \
  -H "Accept: application/vnd.github.v3+upload" \
  -H "Authorization: Bearer ${GITHUB_TOKEN}" \
  --header "Content-Type: application/octet-stream" \
  --data-binary @runwayhub.tar.gz)

echo ${UPLOAD_RESPONSE} | jq .

