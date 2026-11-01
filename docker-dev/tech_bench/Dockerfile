################################################################################
#                                                                              #
#             Tech Bench build for dev Tech Bench Application                  #
#                                                                              #
################################################################################

FROM butcherman/tech_bench_baseline:2.0

################################################################################
# PHP Configuration
################################################################################

# Install xDebug to allow PHPUnit Testing
RUN install-php-extensions xdebug

# Overwrite the Production php.ini file with the dev one
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Copy the .ini file for additional debug configuration
COPY ./php-dev.ini $PHP_INI_DIR/conf.d/php-dev.ini

################################################################################
# Development Scripts
################################################################################

COPY ./entrypoint-dev.sh /tb_data/entrypoint.d/entrypoint-dev.sh
RUN chmod 775 /tb_data/entrypoint.d/entrypoint-dev.sh

################################################################################
# Finalize Image
################################################################################

WORKDIR /var/www/html

ENTRYPOINT [ "/tb_data/entrypoint.d/entrypoint-dev.sh" ]

CMD ["php-fpm"]
