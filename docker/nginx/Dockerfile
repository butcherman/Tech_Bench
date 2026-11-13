
# The NGINX image must use the same APP_VERSION as the PHP image.
ARG APP_IMAGE=butcherman/tech_bench_app
ARG APP_VERSION=latest

# Get the public files from the matching Tech Bench application image.
FROM ${APP_IMAGE}:${APP_VERSION} AS tech_bench_app

# Official NGINX image based on Debian Bookworm.
FROM nginx:stable-bookworm

ENV TB_URL=localhost

# Install OpenSSL for initial self-signed certificate generation.
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    openssl \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Copy the public application files, including Vite assets.
COPY --from=tech_bench_app /app/public/ /app/public/

# Install Tech Bench NGINX configurations.
COPY docker/nginx/http.conf /etc/nginx/conf.d/http.conf
COPY docker/nginx/https.conf /etc/nginx/conf.d/https.conf

# Generate a certificate before NGINX starts, if one does not exist.
COPY docker/nginx/entrypoint.sh /docker-entrypoint.d/10-tech-bench-certificate.sh

RUN chmod 755 /docker-entrypoint.d/10-tech-bench-certificate.sh \
    && mkdir -p /app/keystore/private \
    && chown -R nginx:nginx /app/public

EXPOSE 80 443
