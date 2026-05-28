#!/bin/bash

# Create GitHub Release
gh release create v1.0.0 \
  runwayhub.tar.gz \
  --title "RunwayHub v1.0.0" \
  --notes-file .github/RELEASE_NOTES.md \
  --prerelease false

