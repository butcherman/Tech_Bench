#!/bin/bash

################################################################################
#                                                                              #
#                            Tech Bench Setup Script                           #
#                                                                              #
#               Performs first-run application initialization.                 #
#   Application code, Composer dependencies, and frontend assets are already   #
#                        included in the Docker image.                         #
#                                                                              #
################################################################################

set -Eeuo pipefail

APP_ROOT="/app"

echo "Checking Tech Bench application configuration..."

cd "$APP_ROOT"

# ------------------------------------------------------------------------------
# Validate environment
# ------------------------------------------------------------------------------

if [ ! -f "$APP_ROOT/.env" ]; then
    echo "ERROR: Application environment file not found: $APP_ROOT/.env" >&2
    exit 1
fi

mkdir -p "$APP_ROOT/keystore"

# ------------------------------------------------------------------------------
# Laravel application key
# ------------------------------------------------------------------------------

if ! grep -Eq '^APP_KEY=.+$' "$APP_ROOT/.env"; then
    echo "Generating Laravel application key..."
    php artisan key:generate --force
else
    echo "Laravel application key already exists."
fi

# ------------------------------------------------------------------------------
# Laravel Reverb credentials
# ------------------------------------------------------------------------------

if ! grep -Eq '^REVERB_APP_ID=.+$' "$APP_ROOT/.env" \
    || ! grep -Eq '^REVERB_APP_KEY=.+$' "$APP_ROOT/.env" \
    || ! grep -Eq '^REVERB_APP_SECRET=.+$' "$APP_ROOT/.env"; then

    echo "Generating Laravel Reverb credentials..."
    php artisan reverb:generate --force

else
    echo "Laravel Reverb credentials already exist."
fi

echo "Tech Bench setup checks completed."
