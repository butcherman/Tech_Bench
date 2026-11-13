#!/bin/bash

################################################################################
#                                                                              #
#                          Tech Bench Startup Script                           #
#                                                                              #
#   This script initializes the application when necessary and starts the      #
#                    requested Tech Bench service.                             #
#                                                                              #
################################################################################

set -Eeuo pipefail

APP_ROOT="/app"
SCRIPT_ROOT="/scripts"

SERVICE="${SERVICE:-app}"

echo "============================================================"
echo " Starting Tech Bench"
echo " Service: ${SERVICE}"
echo "============================================================"

cd "$APP_ROOT"

# ------------------------------------------------------------------------------
# Application initialization and deployment tasks
# ------------------------------------------------------------------------------

start_app() {
    echo "Preparing Tech Bench application..."

    # Perform first-run credential initialization when required.
    "$SCRIPT_ROOT/setup.sh"

    echo "Application initialization completed."
}

# ------------------------------------------------------------------------------
# Service dispatch
# ------------------------------------------------------------------------------

case "$SERVICE" in

    app)
        start_app

        echo "Starting PHP-FPM..."
        exec php-fpm -F --pid /tmp/php-fpm.pid
        ;;

    horizon)
        echo "Starting Laravel Horizon..."
        exec php artisan horizon
        ;;

    reverb)
        echo "Starting Laravel Reverb..."
        exec php artisan reverb:start
        ;;

    scheduler)
        echo "Starting Laravel Scheduler..."
        exec php artisan schedule:work
        ;;

    *)
        echo "ERROR: Unknown SERVICE value: ${SERVICE}" >&2
        echo "Valid values: app, horizon, reverb, scheduler" >&2
        exit 1
        ;;

esac
