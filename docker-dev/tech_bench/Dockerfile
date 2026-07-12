################################################################################
#                                                                              #
#             Tech Bench build for dev Tech Bench Application                  #
#                                                                              #
################################################################################

FROM butcherman/tech_bench_baseline:2.0

USER root

# Overwrite the Production php.ini file with the dev one
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Copy the entrypoint script in to allow udpates and initial config
COPY ./entrypoint.sh /tb_data/entrypoint.d/entrypoint.sh
RUN chown root:www-data /tb_data/entrypoint.d/entrypoint.sh
RUN chmod 775 /tb_data/entrypoint.d/entrypoint.sh

# Install xDebug to allow PHPUnit Testing
RUN install-php-extensions xdebug

# Finalize Image
USER tbuser
WORKDIR /var/www/html

# Set entrypoint
ENTRYPOINT [ "/tb_data/entrypoint.d/entrypoints.sh" ]
CMD ["php-fpm"]
