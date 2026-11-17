#!/usr/bin/env bash
#
# Builds the two installable zips: the DKM theme and the DKM Core plugin.
#
# The theme zip leaves out the plugin, the reference prototype and everything
# that only matters while developing, so what lands on the server is only what
# the site runs.
#
# Usage: ./build.sh [output directory]

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
NAME="$(basename "$ROOT")"
PARENT="$(dirname "$ROOT")"
OUT="${1:-$ROOT/dist}"

VERSION="$(grep -m1 '^Version:' "$ROOT/style.css" | tr -d ' \r' | cut -d: -f2)"
PLUGIN_VERSION="$(grep -m1 'Version:' "$ROOT/dkm-core/dkm-core.php" | tr -d ' \r' | cut -d: -f2)"

if [ "$NAME" != "dkm" ]; then
	echo "The theme folder must be named dkm, found '$NAME'." >&2
	exit 1
fi

mkdir -p "$OUT"
rm -f "$OUT"/dkm-theme-*.zip "$OUT"/dkm-core-*.zip

echo "DKM theme  $VERSION"
echo "DKM Core   $PLUGIN_VERSION"
echo

# ---- theme ----

( cd "$PARENT" && zip -qr "$OUT/dkm-theme-${VERSION}.zip" dkm \
	-x 'dkm/.git/*' \
	-x 'dkm/.github/*' \
	-x 'dkm/dist/*' \
	-x 'dkm/dkm-core/*' \
	-x 'dkm/reference/*' \
	-x 'dkm/build.sh' \
	-x 'dkm/tools/*' \
	-x 'dkm/DEPLOY.md' \
	-x 'dkm/*.zip' \
	-x '*/.DS_Store' )

echo "wrote $OUT/dkm-theme-${VERSION}.zip"

# ---- plugin ----

( cd "$ROOT" && zip -qr "$OUT/dkm-core-${PLUGIN_VERSION}.zip" dkm-core -x '*/.DS_Store' )

echo "wrote $OUT/dkm-core-${PLUGIN_VERSION}.zip"
echo

ls -lh "$OUT"/*.zip

echo
echo "Sanity check:"
unzip -l "$OUT/dkm-theme-${VERSION}.zip" | { grep -cE 'dkm/(style.css|functions.php|index.php)' || true; } | xargs -I{} echo "  theme core files found: {} of 3"
unzip -l "$OUT/dkm-theme-${VERSION}.zip" | { grep -c 'dkm/reference/' || true; } | xargs -I{} echo "  reference files leaked:  {} (must be 0)"
unzip -l "$OUT/dkm-theme-${VERSION}.zip" | { grep -c 'dkm/dkm-core/' || true; } | xargs -I{} echo "  plugin files leaked:     {} (must be 0)"
