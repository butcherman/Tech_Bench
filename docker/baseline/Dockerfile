
################################################################################
#                                                                              #
#                   Tech Bench PHP 8.2 Baseline Image                         #
#                                                                              #
#    Baseline Tech Bench build does not contain the Tech Bench App             #
#    All versions of Tech Bench start with this OS/runtime build               #
#    Application files and scripts are added in subsequent builds              #
#                                                                              #
################################################################################

FROM php:8.2.35-fpm-bookworm

# Application environment
ENV TB_URL=localhost
ENV ENABLE_HTTPS=true
ENV SERVICE=master

# Version of Mike Farah yq
ARG YQ_VERSION=v4.45.4

################################################################################
# System Dependencies
################################################################################

RUN apt-get update && apt-get upgrade -y --no-install-recommends && \
    apt-get install -y --no-install-recommends \
    curl \
    nano \
    openssl \
    unzip \
    zip \
    mariadb-client \
    libcurl4-openssl-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zlib1g-dev \
    && rm -rf /var/lib/apt/lists/*

################################################################################
# PHP Extensions
################################################################################

# Configure GD
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp

# Install XML extensions
RUN CFLAGS="-I/usr/src/php" docker-php-ext-install -j"$(nproc)" \
    dom \
    xml \
    xmlreader \
    xmlwriter

# Install remaining PHP extensions
RUN docker-php-ext-install -j"$(nproc)" \
    curl \
    gd \
    mbstring \
    mysqli \
    pdo_mysql \
    pcntl \
    intl \
    zip

# Install Redis PHP extension
RUN pecl install redis \
    && docker-php-ext-enable redis

################################################################################
# Install yq
################################################################################

RUN case "$(dpkg --print-architecture)" in \
    amd64) YQ_ARCH=amd64 ;; \
    arm64) YQ_ARCH=arm64 ;; \
    *) echo "Unsupported architecture for yq"; exit 1 ;; \
    esac \
    && curl -fsSL \
    "https://github.com/mikefarah/yq/releases/download/${YQ_VERSION}/yq_linux_${YQ_ARCH}" \
    -o /usr/local/bin/yq \
    && chmod +x /usr/local/bin/yq

################################################################################
# Node.js and npm
################################################################################

RUN apt-get update && apt-get install -y --no-install-recommends \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# Update Node.js and npm
RUN npm install -g n \
    && n stable \
    && hash -r \
    && npm install -g npm@latest

################################################################################
# Composer
################################################################################

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer self-update --2

################################################################################
# Tech Bench Directories
################################################################################

RUN mkdir -p /scripts /staging /app \
    && chmod 775 /app \
    && chmod 777 /staging

################################################################################
# Tech Bench Scripts
################################################################################

COPY ./download_tb.sh /scripts/
COPY ./startup.sh /scripts/
COPY ./update.sh /scripts/
COPY ./setup.sh /scripts/
COPY ./scheduler.sh /scripts/
COPY ./_functions.sh /scripts/
COPY ./install.sh /scripts/

# Set permissions for scripts
RUN chmod 775 /scripts/*

################################################################################
# Finalize Image
################################################################################

WORKDIR /app

ENTRYPOINT ["/scripts/startup.sh"]
