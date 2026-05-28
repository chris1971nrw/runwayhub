#!/bin/bash

# RunwayHub - Release Script
# Automatisch Release mit Tag, Beschreibung und Dateien

# Farben
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Token
GITHUB_TOKEN="${GITHUB_TOKEN:-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}"

# Repository
REPOSITORY="chris1971nrw/runwayhub"

# Version
VERSION="${1:-$(git describe --tags --abbrev=0 || echo 'unknown')}"

echo -e "${BLUE}=== RunwayHub Release Script ===${NC}"
echo -e "${BLUE}Version: ${VERSION}${NC}"
echo -e "${BLUE}Repository: ${REPOSITORY}${NC}"

# Schritt 1: Tag erstellen
echo -e "\n${YELLOW}Schritt 1: Tag erstellen...${NC}"
if [ -z "$(git tag -l "v${VERSION}")" ]; then
    git tag -a "v${VERSION}" -m "Release v${VERSION}"
    echo -e "${GREEN}✅ Tag v${VERSION} erstellt${NC}"
else
    echo -e "${GREEN}✅ Tag v${VERSION} bereits existiert${NC}"
fi

# Schritt 2: Assets packen
echo -e "\n${YELLOW}Schritt 2: Assets packen...${NC}"
ASSET_NAME="runwayhub.tar.gz"
if [ -f "${ASSET_NAME}" ]; then
    echo -e "${GREEN}✅ ${ASSET_NAME} gefunden${NC}"
else
    echo -e "${YELLOW}⚠️  ${ASSET_NAME} nicht gefunden, wird erstellt...${NC}"
    tar -czf "${ASSET_NAME}" -C $(pwd) runwayhub 2>/dev/null || true
    if [ -f "${ASSET_NAME}" ]; then
        echo -e "${GREEN}✅ ${ASSET_NAME} erstellt${NC}"
    else
        echo -e "${RED}❌ ${ASSET_NAME} konnte nicht erstellt werden${NC}"
        exit 1
    fi
fi

# Schritt 3: Release Notes lesen
echo -e "\n${YELLOW}Schritt 3: Release Notes lesen...${NC}"
RELEASE_NOTES_FILE=".github/RELEASE_NOTES.md"
if [ -f "${RELEASE_NOTES_FILE}" ]; then
    RELEASE_NOTES=$(cat "${RELEASE_NOTES_FILE}")
    echo -e "${GREEN}✅ Release Notes gefunden${NC}"
else
    echo -e "${YELLOW}⚠️  Release Notes nicht gefunden, leere Beschreibung${NC}"
    RELEASE_NOTES=""
fi

# Schritt 4: Release erstellen mit API
echo -e "\n${YELLOW}Schritt 4: Release erstellen...${NC}"
RELEASE_DATA=$(
cat <<EOF
{
  "name": "RunwayHub v${VERSION}",
  "body": "${RELEASE_NOTES:-"Keine Beschreibung."}",
  "draft": false,
  "prerelease": false
}
EOF
)

HTTP_CODE=$(curl -s -w "%{http_code}" -X POST "https://api.github.com/repos/${REPOSITORY}/releases/tags/v${VERSION}" \
  -H "Accept: application/vnd.github.v3+json" \
  -H "Authorization: Bearer ${GITHUB_TOKEN}" \
  -H "Content-Type: application/json" \
  -d "${RELEASE_DATA}" | tail -1)

echo -e "HTTP Code: ${HTTP_CODE}"

if [ "${HTTP_CODE}" == "201" ]; then
    echo -e "${GREEN}✅ Release erstellt!${NC}"
    ASSET_URL="https://api.github.com/repos/${REPOSITORY}/releases/tags/v${VERSION}/assets"
    
    # Schritt 5: Asset hochladen
    echo -e "\n${YELLOW}Schritt 5: Asset hochladen...${NC}"
    
    UPLOAD_URL=$(curl -s -X POST "${ASSET_URL}" \
      -H "Accept: application/vnd.github.v3+upload" \
      -H "Authorization: Bearer ${GITHUB_TOKEN}" \
      -w "%{url_effective}" | tail -c -200)
    
    echo -e "Upload URL: ${UPLOAD_URL}"
    
    UPLOAD_SUCCESS=$(curl -s -w "%{http_code}" -X POST "${UPLOAD_URL}" \
      -H "Accept: application/vnd.github.v3+upload" \
      -H "Authorization: Bearer ${GITHUB_TOKEN}" \
      -H "Content-Type: application/octet-stream" \
      --data-binary @${ASSET_NAME} | tail -1)
    
    echo -e "Upload HTTP Code: ${UPLOAD_SUCCESS}"
    
    if [ "${UPLOAD_SUCCESS}" == "201" ]; then
        echo -e "${GREEN}✅ Asset hochgeladen!${NC}"
    else
        echo -e "${RED}❌ Upload fehlgeschlagen${NC}"
    fi
else
    echo -e "${RED}❌ Release-Erstellung fehlgeschlagen${NC}"
    echo -e "Fehler: ${RELEASE_NOTES}"
fi

# Schritt 6: Push
echo -e "\n${YELLOW}Schritt 6: Tag pushen...${NC}"
git push origin "v${VERSION}"
echo -e "${GREEN}✅ Tag pushen erfolgreich${NC}"

# Schritt 7: Release URL
echo -e "\n${BLUE}=== Release fertig! ===${NC}"
echo -e "${BLUE}Release URL: https://github.com/${REPOSITORY}/releases/tag/v${VERSION}${NC}"
echo -e "${BLUE}Asset: ${ASSET_NAME}${NC}"

# Cleanup
rm -f "${ASSET_NAME}"
echo -e "${GREEN}✅ Cleanup durchgeführt${NC}"
