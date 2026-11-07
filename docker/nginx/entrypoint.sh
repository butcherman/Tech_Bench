
#!/bin/sh

set -eu

echo "Starting Tech Bench NGINX setup"

CERT_FILE="/app/keystore/server.crt"
KEY_FILE="/app/keystore/private/server.key"

# Generate a self-signed certificate if either file is missing.
if [ ! -f "$CERT_FILE" ] || [ ! -f "$KEY_FILE" ]; then

    echo "Creating self-signed SSL certificate"

    mkdir -p /app/keystore/private

    # Extract the hostname from TB_URL.
    CERT_HOST="${TB_URL:-localhost}"
    CERT_HOST="${CERT_HOST#*://}"
    CERT_HOST="${CERT_HOST%%/*}"
    CERT_HOST="${CERT_HOST%%:*}"

    # Use an IP or DNS subjectAltName as appropriate.
    case "$CERT_HOST" in
        *[!0-9.]*)
            SAN="DNS:${CERT_HOST}"
            ;;
        *)
            SAN="IP:${CERT_HOST}"
            ;;
    esac

    openssl req -x509 \
        -nodes \
        -newkey rsa:2048 \
        -sha256 \
        -days 3650 \
        -keyout "$KEY_FILE" \
        -out "$CERT_FILE" \
        -subj "/CN=${CERT_HOST}" \
        -addext "subjectAltName=${SAN}"

    chmod 644 "$CERT_FILE"
    chmod 640 "$KEY_FILE"
    chown root:nginx "$KEY_FILE"

    echo "Self-signed SSL certificate created"
else
    echo "Existing SSL certificate found; skipping generation"
fi
