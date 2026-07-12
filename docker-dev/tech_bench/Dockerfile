################################################################################
#                                                                              #
#             Tech Bench build for main Tech Bench Application                 #
#                                                                              #
################################################################################

FROM butcherman/tech_bench_baseline:2.0

USER root

# Overwrite the Production php.ini file with the dev one
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Install xDebug to allow PHPUnit Testing
RUN install-php-extensions xdebug

# Finalize Image
USER www-data
WORKDIR /var/www/html
